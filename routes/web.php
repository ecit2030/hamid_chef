<?php

/**
 * Web Routes
 * 
 * This file contains public web routes only.
 * 
 * Authentication:
 * - Customers: API only (Sanctum) - see routes/api.php
 * - Chefs: /chef/* routes - see routes/chef.php
 * - Admins: /admin/* routes - see routes/admin.php
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Site\LandingPageController;
use App\Http\Controllers\Site\ContactController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Contact Form
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Locale Switcher
Route::post('/locale/switch', function () {
    $locale = request('locale', 'ar');
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return back();
})->name('locale.switch');

Route::post('/locale', LocaleController::class)->name('locale.set')->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| Auth Routes (Disabled for Customers)
|--------------------------------------------------------------------------
|
| Customer web login has been disabled.
| The auth.php file now redirects all routes to landing page.
|
*/

require __DIR__.'/auth.php';
