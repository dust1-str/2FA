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
 * Redirect to the login form or home when accessing the root URL.
 * If the user is authenticated, they will be redirected to the home page.
 * If the user is not authenticated, they will be redirected to the login form.
 * This happens because of the 'guest' middleware that protects the login route.
 */
Route::get('/', function () {
    return redirect()->route('login');
});

/**
 * Routes protected by authentication and email verification middleware.
 */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('home', function () {
        return view('home');
    })->name('home');
});

/**
 * Fallback route for handling 404 errors
 */
Route::fallback(function () {
    return view('errors.404');
});