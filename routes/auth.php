<?php

/**
 * Web Authentication Routes
 * 
 * NOTE: Customer web login has been disabled.
 * Customers should authenticate via API only (Sanctum).
 * 
 * The /login route now redirects to the landing page.
 * Chefs should use /chef/login
 * Admins should use /admin/login
 */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Disabled Customer Web Login
|--------------------------------------------------------------------------
| 
| Customer authentication is now API-only via Sanctum.
| The following routes redirect to appropriate pages.
|
*/

Route::middleware('guest:web')->group(function () {
    // Redirect /login to landing page (customers use API)
    Route::get('login', function () {
        return redirect()->route('landing');
    })->name('login');

    // Redirect POST /login to landing page
    Route::post('login', function () {
        return redirect()->route('landing');
    });

    // Redirect register to landing page
    Route::get('register', function () {
        return redirect()->route('landing');
    })->name('register');

    Route::post('register', function () {
        return redirect()->route('landing');
    });

    // Keep password reset for API users who might need it via web
    // These can be removed if not needed
    Route::get('forgot-password', function () {
        return redirect()->route('landing');
    })->name('password.request');

    Route::post('forgot-password', function () {
        return redirect()->route('landing');
    })->name('password.email');

    Route::get('reset-password/{token}', function () {
        return redirect()->route('landing');
    })->name('password.reset');

    Route::post('reset-password', function () {
        return redirect()->route('landing');
    })->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Disabled for Web Guard)
|--------------------------------------------------------------------------
|
| These routes are no longer needed for web guard since customers
| authenticate via API only.
|
*/

Route::middleware('auth:web')->group(function () {
    // Redirect any authenticated web user to landing
    // (This shouldn't happen since login is disabled)
    Route::get('verify-email', function () {
        return redirect()->route('landing');
    })->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', function () {
        return redirect()->route('landing');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('email/verification-notification', function () {
        return redirect()->route('landing');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::get('confirm-password', function () {
        return redirect()->route('landing');
    })->name('password.confirm');

    Route::post('confirm-password', function () {
        return redirect()->route('landing');
    });

    Route::put('password', function () {
        return redirect()->route('landing');
    })->name('password.update');

    Route::post('logout', function () {
        return redirect()->route('landing');
    })->name('logout');
});
