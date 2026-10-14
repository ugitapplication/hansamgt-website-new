<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/company-profile', [HomeController::class, 'history'])->name('company-profile');
Route::get('/mission-vision', [HomeController::class, 'vision'])->name('mission-vision');
Route::get('/charter-of-unique-group', [HomeController::class, 'charter'])->name('charter-of-unique-group');
Route::get('/management', [HomeController::class, 'management'])->name('management');
Route::get('/corporate-social-responsibility', [HomeController::class, 'csr'])->name('corporate-social-responsibility');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/hospitality-management', [HomeController::class, 'hardService'])->name('hospitality-management');
Route::get('/security-fire-life-safety', [HomeController::class, 'softService'])->name('security-fire-life-safety');
Route::get('/cleaning-white-glove-housekeeping', [HomeController::class, 'payperUse'])->name('cleaning-white-glove-housekeeping');
Route::get('/mep-mechanical-electrical-plumbing', [HomeController::class, 'riskManagement'])->name('mep-mechanical-electrical-plumbing');
Route::get('/projects', [HomeController::class, 'project'])->name('projects');
Route::get('/gallery', [HomeController::class, 'siteManagement'])->name('gallery');
