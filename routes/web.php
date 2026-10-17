<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InsightController;
use App\Http\Middleware\RedirectIndexPhp;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/company-profile', [HomeController::class, 'history'])->name('company-profile');
Route::get('/mission-vision', [HomeController::class, 'vision'])->name('mission-vision');
Route::get('/charter-of-unique-group', [HomeController::class, 'charter'])->name('charter-of-unique-group');
Route::get('/management', [HomeController::class, 'management'])->name('management');
Route::get('/corporate-social-responsibility', [HomeController::class, 'csr'])->name('corporate-social-responsibility');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');
Route::get('/hospitality-management', [HomeController::class, 'hardService'])->name('hospitality-management');
Route::get('/security-fire-life-safety', [HomeController::class, 'softService'])->name('security-fire-life-safety');
Route::get('/cleaning-white-glove-housekeeping', [HomeController::class, 'payperUse'])->name('cleaning-white-glove-housekeeping');
Route::get('/mep-mechanical-electrical-plumbing', [HomeController::class, 'riskManagement'])->name('mep-mechanical-electrical-plumbing');
Route::get('/projects', [HomeController::class, 'project'])->name('projects');
Route::get('/gallery', [HomeController::class, 'siteManagement'])->name('gallery');
Route::get('/careers', [HomeController::class, 'careers'])->name('careers');

// Legal. A single privacy policy page covers the cookie disclosure and the
// consent notice, so there is no separate cookie policy to keep in step.
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy-policy');

// Insights (News) — CMS-driven articles. The feed route is declared before the
// wildcard so "/insights/feed" is not swallowed by "{slug}".
Route::get('/insights', [InsightController::class, 'index'])->name('insights');
Route::get('/insights/feed', [InsightController::class, 'feed'])->name('insights.feed');
Route::get('/insights/{slug}', [InsightController::class, 'show'])->name('insights.show');

// Legacy CodeIgniter URLs (home/*) → clean canonical URLs.
// SEO 301s so indexed/cached old addresses keep their traffic (query string preserved).
// GET-only on purpose: form POSTs must never be redirected (legacy parity).
$legacyRedirects = RedirectIndexPhp::LEGACY_MAP;

foreach ($legacyRedirects as $old => $new) {
    $segments = [$old];
    $lower = strtolower($old);
    if ($lower !== $old) {
        $segments[] = $lower; // tolerate case-mangled links to camelCase URLs
    }
    foreach ($segments as $segment) {
        Route::get('/home/'.$segment, function () use ($new) {
            $qs = request()->getQueryString();
            return redirect('/'.ltrim($new, '/').($qs ? '?'.$qs : ''), 301);
        });
    }
}

Route::get('/home', function () {
    $qs = request()->getQueryString();
    return redirect('/'.($qs ? '?'.$qs : ''), 301);
});
