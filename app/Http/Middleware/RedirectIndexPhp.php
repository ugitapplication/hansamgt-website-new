<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIndexPhp
{
    /**
     * Legacy CodeIgniter segments → clean canonical slugs.
     * Single source of truth, reused by routes/web.php.
     */
    public const LEGACY_MAP = [
        'history' => 'company-profile',
        'vision' => 'mission-vision',
        'charter' => 'charter-of-unique-group',
        'management' => 'management',
        'csr' => 'corporate-social-responsibility',
        'contact' => 'contact',
        'hardService' => 'hospitality-management',
        'softService' => 'security-fire-life-safety',
        'payperUse' => 'cleaning-white-glove-housekeeping',
        'riskManagement' => 'mep-mechanical-electrical-plumbing',
        'project' => 'projects',
        'siteManagement' => 'gallery',
        'index' => '/',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Never redirect form submissions (legacy parity).
        if (! $request->isMethodCacheable()) {
            return $next($request);
        }

        // Raw URI: Request::path() strips the script name, so inspect the raw value.
        $path = parse_url($request->getRequestUri(), PHP_URL_PATH) ?? '';

        if ($path === '/index.php' || str_starts_with($path, '/index.php/')) {
            $clean = substr($path, strlen('/index.php'));
            if ($clean === '' || $clean === false) {
                $clean = '/';
            }

            // Single-hop: resolve legacy segments straight to the canonical target.
            $target = self::legacyTarget($clean) ?? $clean;

            $qs = $request->getQueryString();

            // Absolute URL from scheme+host on purpose: when PHP executes
            // public/index.php directly, the URL generator treats "/index.php"
            // as the app's base path, so redirect('/') would point back onto
            // /index.php/... and loop forever.
            $url = $request->getSchemeAndHttpHost().$target.($qs ? '?'.$qs : '');

            return redirect($url, 301);
        }

        return $next($request);
    }

    public static function legacyTarget(string $path): ?string
    {
        if ($path === '/home') {
            return '/';
        }

        if (preg_match('#^/home/([^/]+)/?$#', $path, $m)) {
            $segment = $m[1];
            $hit = self::LEGACY_MAP[$segment] ?? self::LEGACY_MAP[strtolower($segment)] ?? null;
            if ($hit !== null) {
                return '/'.ltrim($hit, '/');
            }
        }

        return null;
    }
}
