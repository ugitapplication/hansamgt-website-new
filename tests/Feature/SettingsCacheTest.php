<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Support\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The shared, cross-request cache behind Settings::all().
 *
 * The per-request static inside Settings cannot be reached from a test, so
 * these tests work from outside the class. The lever that makes that possible:
 * a value that comes back while the database cannot be read, or while the store
 * disagrees with the table, can only have come from the shared cache.
 *
 * Failure simulation is deliberately not a dropped table. Dropping a table
 * inside RefreshDatabase is only rolled back by drivers with transactional DDL
 * (SQLite, PostgreSQL); on MySQL it would commit and wreck every later test in
 * the run. Instead:
 *
 *  - a database failure is a default connection that does not exist, which
 *    throws the same Throwable the catch block was written for, touches no
 *    schema, and is restored in a finally block;
 *  - a store failure is a cache store whose table does not exist, which is what
 *    an unavailable `database` store looks like in production.
 *    DatabaseStore::get() has no catch of its own, so the QueryException really
 *    does reach Settings.
 *
 * phpunit.xml sets CACHE_STORE=array, so the store here is per-test: Laravel
 * builds a fresh application for every test, and with it a fresh ArrayStore.
 */
class SettingsCacheTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Mirrors Settings::CACHE_KEY deliberately — renaming the real key is a
     * cache-compatibility decision, and this should have to change with it.
     */
    private const CACHE_KEY = 'site-settings:v1';

    /**
     * The class's statics outlive the application, so they are dropped
     * explicitly; without this a value read in one test would be served to the
     * next one from memory.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Settings::flush();
    }

    public function test_the_request_local_snapshot_does_not_leak_between_tests(): void
    {
        // setUp() flushed the statics and the store starts empty, so nothing
        // from an earlier test can be visible here.
        $this->assertNull(Cache::get(self::CACHE_KEY));

        Setting::query()->create(['key' => 'email', 'value' => 'snapshot@example.com']);

        // The first read is this request's snapshot and deliberately does not
        // change when the table changes underneath it...
        $this->assertSame('snapshot@example.com', Settings::get('email'));

        Setting::query()->where('key', 'email')->update(['value' => 'later@example.com']);

        $this->assertSame('snapshot@example.com', Settings::get('email'));

        // ...and flush(), which setUp() calls before every test, is what drops
        // it. That is the isolation this test asserts.
        Settings::flush();

        $this->assertSame('later@example.com', Settings::get('email'));
    }

    public function test_a_successful_read_stores_the_raw_rows_in_the_shared_cache(): void
    {
        Setting::query()->create(['key' => 'email', 'value' => 'info@example.com']);
        Setting::query()->create(['key' => 'phone_primary', 'value' => '+8801000000000']);

        $values = Settings::all();

        $this->assertSame('info@example.com', $values['email']);
        $this->assertSame('+8801000000000', $values['phone_primary']);
        $this->assertSame($values, Cache::get(self::CACHE_KEY));
    }

    public function test_an_empty_settings_table_is_cached_as_a_successful_read(): void
    {
        $this->assertSame([], Settings::all());

        // A miss would be null. An empty array is a real answer that was stored,
        // and it must stay distinguishable from the failure fallback.
        $this->assertSame([], Cache::get(self::CACHE_KEY));
        $this->assertTrue(Cache::has(self::CACHE_KEY));
    }

    public function test_a_warm_cache_supplies_the_stored_values(): void
    {
        Setting::query()->create(['key' => 'email', 'value' => 'from-the-database@example.com']);

        Settings::flush();

        Cache::put(self::CACHE_KEY, ['email' => 'from-the-cache@example.com'], 60);

        // Only a read that consults the shared cache can answer this: the table
        // says something else. The database is never asked.
        $this->assertSame('from-the-cache@example.com', Settings::get('email'));
    }

    public function test_a_failed_database_read_returns_defaults_and_poisons_nothing(): void
    {
        $original = config('database.default');

        try {
            config(['database.default' => 'hansa-connection-that-does-not-exist']);

            Settings::flush();

            // Exactly the old behaviour: no exception, and the defaults from
            // config/site.php take over.
            $this->assertSame([], Settings::all());
            $this->assertSame(config('site.defaults.email'), Settings::get('email'));

            // And the fallback was not written down for the next request.
            $this->assertNull(Cache::get(self::CACHE_KEY));
            $this->assertFalse(Cache::has(self::CACHE_KEY));
        } finally {
            config(['database.default' => $original]);
        }
    }

    public function test_a_successful_read_after_a_failed_one_recovers_and_populates_the_cache(): void
    {
        $original = config('database.default');

        try {
            config(['database.default' => 'hansa-connection-that-does-not-exist']);

            Settings::flush();

            $this->assertSame([], Settings::all());
            $this->assertFalse(Cache::has(self::CACHE_KEY));
        } finally {
            config(['database.default' => $original]);
        }

        Setting::query()->create(['key' => 'email', 'value' => 'recovered@example.com']);

        // The failed read left nothing behind, so the next request reads the
        // table and stores the real answer.
        Settings::flush();

        $this->assertSame('recovered@example.com', Settings::get('email'));
        $this->assertSame(['email' => 'recovered@example.com'], Cache::get(self::CACHE_KEY));
    }

    public function test_set_invalidates_the_shared_cache_for_the_next_request(): void
    {
        Setting::query()->create(['key' => 'email', 'value' => 'old@example.com']);
        Settings::all();

        $this->assertNotNull(Cache::get(self::CACHE_KEY));

        Settings::set('email', 'new@example.com');

        // Gone, not merely stale.
        $this->assertNull(Cache::get(self::CACHE_KEY));

        // The next read re-queries and stores the new value.
        $this->assertSame('new@example.com', Settings::get('email'));
        $this->assertSame(['email' => 'new@example.com'], Cache::get(self::CACHE_KEY));
    }

    public function test_set_many_invalidates_the_shared_cache(): void
    {
        Setting::query()->create(['key' => 'phone_primary', 'value' => '+8801000000000']);
        Settings::all();

        $this->assertNotNull(Cache::get(self::CACHE_KEY));

        // What ManageSettings::save() calls, including the boolean encoding.
        Settings::setMany([
            'phone_primary' => '+8801999999999',
            'ga4_enabled' => true,
        ]);

        $this->assertNull(Cache::get(self::CACHE_KEY));

        $this->assertSame('+8801999999999', Settings::get('phone_primary'));
        $this->assertSame('1', Settings::get('ga4_enabled'));

        $cached = Cache::get(self::CACHE_KEY);

        $this->assertCount(2, $cached);
        $this->assertSame('+8801999999999', $cached['phone_primary']);
        $this->assertSame('1', $cached['ga4_enabled']);
    }

    public function test_the_cached_payload_holds_raw_paths_not_resolved_urls(): void
    {
        Storage::fake('public', ['url' => 'http://localhost/storage']);
        Storage::disk('public')->put('branding/logo.png', 'x');

        Settings::set('logo', 'branding/logo.png');

        // Reading repopulates the cache that set() just cleared.
        $this->assertSame('branding/logo.png', Settings::get('logo'));

        $cached = Cache::get(self::CACHE_KEY);

        // The raw stored value, host-independent, so safe to share.
        $this->assertSame('branding/logo.png', $cached['logo']);
        $this->assertStringNotContainsString('http', $cached['logo']);

        // The resolved URL depends on APP_URL and the request host, and stays a
        // per-request memo: the shared payload must never look like this.
        $this->assertSame(
            'http://localhost/storage/branding/logo.png',
            Settings::imageUrl('logo'),
        );
        $this->assertStringNotContainsString('storage', $cached['logo']);
    }

    public function test_an_unavailable_cache_store_does_not_break_reads_or_invalidation(): void
    {
        Setting::query()->create(['key' => 'email', 'value' => 'real@example.com']);

        $original = config('cache.default');

        try {
            // A `database` store pointed at a table that does not exist: every
            // read, write and forget throws a QueryException. This is what an
            // unavailable database-backed cache looks like in production.
            config(['cache.stores.unavailable' => [
                'driver' => 'database',
                'connection' => null,
                'table' => 'cache_table_that_does_not_exist',
            ]]);

            config(['cache.default' => 'unavailable']);

            // No page-rendering exception, and the value still arrives.
            $this->assertSame('real@example.com', Settings::get('email'));

            // Invalidation must not throw either, and the write must still work.
            Settings::set('email', 'changed@example.com');

            $this->assertSame('changed@example.com', Settings::get('email'));
        } finally {
            config(['cache.default' => $original]);
        }
    }
}
