<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Guards the static-asset cache directives in public/.htaccess.
 *
 * IMPORTANT: this test reads a configuration FILE. It proves which directives
 * are present in the repository — it does NOT prove which headers the deployed
 * web server actually returns. That depends on the host (Apache vs Nginx),
 * whether .htaccess is honoured, whether it allows the directives, and whether
 * mod_headers / mod_setenvif are loaded. Real headers must be checked on the
 * live host with `curl -I` (see docs/HANSA-website-migration-report.md §8.1).
 */
class StaticAssetCacheHeadersTest extends TestCase
{
    /**
     * Read public/.htaccess, asserting it exists first.
     */
    private function htaccess(): string
    {
        $path = public_path('.htaccess');

        $this->assertFileExists($path, 'public/.htaccess must exist and be tracked.');

        return (string) file_get_contents($path);
    }

    public function test_hashed_vite_build_assets_are_cached_immutably_for_one_year(): void
    {
        $contents = $this->htaccess();

        $this->assertStringContainsString('SetEnvIf Request_URI "^/build/"', $contents);
        $this->assertStringContainsString(
            'Header set Cache-Control "public, max-age=31536000, immutable" env=hansa_hashed_asset',
            $contents,
        );
    }

    public function test_replaceable_assets_get_a_bounded_lifetime_and_are_not_immutable(): void
    {
        $contents = $this->htaccess();

        $this->assertStringContainsString('SetEnvIf Request_URI "^/(images|fonts)/"', $contents);
        $this->assertStringContainsString(
            'Header set Cache-Control "public, max-age=604800" env=hansa_replaceable_asset',
            $contents,
        );

        // Replaceable files are overwritten in place under the same URL, so a
        // one-year immutable policy must never be attached to them.
        $this->assertStringNotContainsString(
            'max-age=31536000, immutable" env=hansa_replaceable_asset',
            $contents,
        );
    }

    public function test_versioned_filament_assets_get_the_bounded_thirty_day_policy(): void
    {
        $contents = $this->htaccess();

        $this->assertStringContainsString('SetEnvIf Request_URI "^/(css|js)/"', $contents);
        $this->assertStringContainsString(
            'Header set Cache-Control "public, max-age=2592000" env=hansa_versioned_asset',
            $contents,
        );
    }

    public function test_every_cache_control_rule_is_scoped_so_it_cannot_apply_to_every_url(): void
    {
        $contents = $this->htaccess();

        preg_match_all(
            '/^\s*Header\s+(?:always\s+)?set\s+Cache-Control\s+(?<rest>.*)$/im',
            $contents,
            $matches,
        );

        $this->assertNotEmpty(
            $matches['rest'],
            'Expected at least one Cache-Control directive in public/.htaccess.',
        );

        foreach ($matches['rest'] as $rest) {
            $this->assertStringContainsString(
                'env=',
                $rest,
                'Every Cache-Control rule must be scoped with env= so it cannot apply to every URL.',
            );
        }
    }

    public function test_cache_headers_are_not_applied_to_error_responses(): void
    {
        // `Header set` (without `always`) targets successful responses only, so
        // using `Header always set` would wrongly cache 4xx/5xx responses.
        $this->assertStringNotContainsString(
            'Header always set Cache-Control',
            $this->htaccess(),
        );
    }

    public function test_existing_routing_and_rewrite_rules_are_preserved(): void
    {
        $contents = $this->htaccess();

        $this->assertStringContainsString('RewriteEngine On', $contents);
        $this->assertStringContainsString('RewriteRule ^index\.php - [L]', $contents);
        $this->assertStringContainsString(
            'RewriteRule ^index\.php/home/history/?$ /company-profile [L,R=301,QSA,NC]',
            $contents,
        );
        $this->assertStringContainsString('RewriteRule ^ index.php [L]', $contents);
    }
}
