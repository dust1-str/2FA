<?php
use Illuminate\Support\Facades\Route;

/**
 * Import authentication routes.
 */
require __DIR__.'/auth.php';

/**
 * Import OTP verification routes.
 */
require __DIR__.'/otp.php';

/**
 * Routes protected by authentication and email verification middleware.
 */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('home', function () {
        return view('home');
    })->name('home');
});

//Fallback route for handling 404 errors
Route::fallback(function () {
    return view('errors.404');
});