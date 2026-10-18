<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $isAdmin = $request->is('admin', 'admin/*');

        // Don't advertise the PHP version.
        $response->headers->remove('X-Powered-By');
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Permissions-Policy' => 'geolocation=(), microphone=(), camera=(), payment=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
        ];

        foreach ($headers as $key => $value) {
            if (! $response->headers->has($key)) {
                $response->headers->set($key, $value);
            }
        }

        // Never let Google index the admin panel or error responses.
        if ($isAdmin) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');
        } elseif ($response->getStatusCode() >= 400) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        // HSTS only over HTTPS (safe on plain-HTTP local dev).
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if (! $response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($isAdmin));
        }

        return $response;
    }

    protected function contentSecurityPolicy(bool $isAdmin = false): string
    {
        $script = "'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com";
        $style = "'self' 'unsafe-inline'";
        $connect = "'self' https://www.google-analytics.com https://region1.google-analytics.com https://www.googletagmanager.com";
        $img = ["'self'", 'data:', 'https://www.google-analytics.com', 'https://www.googletagmanager.com'];

        // The office map is a Google Maps iframe. The keyless embed URL needs
        // exactly one extra origin, derived from the same configuration value
        // the embed URL is built from — so the policy and the iframe cannot
        // drift apart. No wildcard, no sub-domains, and no extra origin at all
        // when the feature is switched off. 'self' is kept because it is what
        // the previous frame-src fallback (default-src) already allowed.
        $frame = ["'self'"];

        if (config('site.maps.enabled')) {
            $googleOrigin = $this->origin((string) config('site.maps.endpoint'));

            if ($googleOrigin !== null) {
                $frame[] = $googleOrigin;
            }
        }

        // Filament / Livewire / Alpine evaluate expressions at runtime, which
        // needs 'unsafe-eval'. The admin panel is private and noindexed, so the
        // public site keeps the stricter policy.
        if ($isAdmin) {
            $script .= " 'unsafe-eval'";
            $connect .= ' ws: wss:';
        }

        // Allow the Vite dev server when working locally so `npm run dev` still works.
        if (app()->environment('local')) {
            $vite = 'http://localhost:5173 http://127.0.0.1:5173';
            $script .= ' '.$vite;
            $style .= ' '.$vite;
            $connect .= ' '.$vite.' ws://localhost:5173 ws://127.0.0.1:5173';
        }

        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            'frame-src '.implode(' ', array_unique($frame)),
            "form-action 'self'",
            'img-src '.implode(' ', array_unique($img)),
            "font-src 'self' data:",
            'script-src '.$script,
            'style-src '.$style,
            'connect-src '.$connect,
        ]);
    }

    /**
     * The scheme://host origin of a URL, for use in a CSP source list.
     */
    protected function origin(string $url): ?string
    {
        $parts = parse_url($url);

        if (! isset($parts['scheme'], $parts['host'])) {
            return null;
        }

        return $parts['scheme'].'://'.$parts['host'];
    }
}
