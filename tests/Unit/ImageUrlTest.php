<?php

namespace Tests\Unit;

use App\Support\ImageUrl;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Mockery;
use RuntimeException;
use Tests\TestCase;

/**
 * ImageUrl::resolve() memoises each path for the lifetime of the process.
 *
 * That memo is a `static` array, so inside one PHPUnit run it survives from one
 * test method to the next exactly as it survives a whole page render.
 * ImageUrl::flush() in setUp() gives every test a clean slate.
 *
 * How "the disk was only checked once" is proved: two techniques, neither of
 * which reaches into framework internals.
 *
 *  - Where a real result is wanted, the fake disk is used and the file is
 *    deleted between the two calls. A second existence check would now take the
 *    asset() branch, so getting the same public-disk URL back can only mean the
 *    check did not run again. A flushed control call at the end proves the
 *    deletion really did change the answer.
 *  - Where a specific failure has to be forced, Storage is mocked and the mock
 *    counts its own calls; the count is asserted explicitly rather than left to
 *    a tear-down expectation.
 */
class ImageUrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ImageUrl::flush();
    }

    public function test_the_same_path_is_checked_against_the_disk_only_once(): void
    {
        Storage::fake('public', ['url' => 'http://localhost/storage']);
        Storage::disk('public')->put('images/once.jpg', 'x');

        $first = ImageUrl::resolve('images/once.jpg');
        $this->assertSame('http://localhost/storage/images/once.jpg', $first);

        // If the existence check ran again it would now report a miss and the
        // asset() fallback would come back instead.
        Storage::disk('public')->delete('images/once.jpg');

        $this->assertSame($first, ImageUrl::resolve('images/once.jpg'));

        // Control: with the memo dropped, the same call does see the deletion —
        // so the assertion above really is about memoisation, not about delete().
        ImageUrl::flush();

        $this->assertSame(asset('images/once.jpg'), ImageUrl::resolve('images/once.jpg'));
    }

    public function test_an_existing_file_resolves_to_the_public_disk_url(): void
    {
        Storage::fake('public', ['url' => 'http://localhost/storage']);
        Storage::disk('public')->put('branding/logo.png', 'x');

        $this->assertSame(
            url('http://localhost/storage/branding/logo.png'),
            ImageUrl::resolve('branding/logo.png'),
        );
    }

    public function test_a_missing_file_falls_back_to_the_public_asset_url(): void
    {
        Storage::fake('public', ['url' => 'http://localhost/storage']);

        $this->assertSame(
            asset('images/missing.jpg'),
            ImageUrl::resolve('images/missing.jpg'),
        );
    }

    public function test_different_paths_are_resolved_independently(): void
    {
        Storage::fake('public', ['url' => 'http://localhost/storage']);
        Storage::disk('public')->put('images/present.jpg', 'x');

        $present = ImageUrl::resolve('images/present.jpg');
        $absent = ImageUrl::resolve('images/absent.jpg');

        $this->assertSame(url('http://localhost/storage/images/present.jpg'), $present);
        $this->assertSame(asset('images/absent.jpg'), $absent);
        $this->assertNotSame($present, $absent);

        // Resolving the first path again after the second must still find its
        // own entry, not the second path's.
        $this->assertSame($present, ImageUrl::resolve('images/present.jpg'));
    }

    public function test_absolute_urls_are_returned_unchanged(): void
    {
        $this->assertSame(
            'https://cdn.example.com/logo.png',
            ImageUrl::resolve('https://cdn.example.com/logo.png'),
        );

        $this->assertSame(
            'http://cdn.example.com/logo.png',
            ImageUrl::resolve('http://cdn.example.com/logo.png'),
        );
    }

    public function test_null_empty_and_blank_paths_resolve_to_an_empty_string(): void
    {
        $this->assertSame('', ImageUrl::resolve(null));
        $this->assertSame('', ImageUrl::resolve(''));
        $this->assertSame('', ImageUrl::resolve('   '));
    }

    public function test_surrounding_whitespace_lands_in_the_same_memo_entry(): void
    {
        Storage::fake('public', ['url' => 'http://localhost/storage']);
        Storage::disk('public')->put('branding/logo.png', 'x');

        $padded = ImageUrl::resolve("  branding/logo.png\t");

        Storage::disk('public')->delete('branding/logo.png');

        // The padded form and the clean form are one entry, keyed by the
        // trimmed path — so this is answered without touching the disk again.
        $this->assertSame($padded, ImageUrl::resolve('branding/logo.png'));
        $this->assertSame('http://localhost/storage/branding/logo.png', $padded);
    }

    public function test_a_disk_error_is_never_remembered(): void
    {
        $checks = 0;

        $disk = Mockery::mock(Filesystem::class);
        $disk->shouldReceive('exists')->andReturnUsing(function () use (&$checks): bool {
            $checks++;

            if ($checks === 1) {
                throw new RuntimeException('disk unavailable');
            }

            // The retry reports a miss, which keeps this test to exists() only.
            return false;
        });

        Storage::shouldReceive('disk')->with('public')->andReturn($disk);

        try {
            ImageUrl::resolve('images/flaky.jpg');
            $this->fail('A disk error should propagate, exactly as it did before.');
        } catch (RuntimeException) {
            // Expected.
        }

        // Nothing was memoised, so the retry reaches the disk again instead of
        // replaying the failure.
        $this->assertSame(asset('images/flaky.jpg'), ImageUrl::resolve('images/flaky.jpg'));
        $this->assertSame(2, $checks);
    }
}
