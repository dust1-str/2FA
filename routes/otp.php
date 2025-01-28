<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;

/**
 * OTP Verification Routes
 * These routes handle the OTP (One-Time Password) verification process.
 * 'logged' middleware is used to check if the user is coming from the login form or is retrying to verify their OTP.
 */

//Displays the OTP form for the user to enter their OTP.
Route::get('otp/{id}', [OtpController::class, 'showOtpForm'])
    ->middleware('logged')
    ->whereNumber('id')
    ->name('otp.form');

//Handles the verification of the OTP entered by the user.
Route::post('otp/{id}', [OtpController::class, 'verifyOtp'])
    ->whereNumber('id')
    ->name('otp.verify');

/**
 * Resend OTP Routes
 * These routes handle the resending of the OTP.
 */

/**
 * Redirect to home if accessing '/resend-otp/{id}' via GET.
 * The POST '/resend-otp/{id}' route should be used for resending the OTP.
 * Protected by the 'auth' middleware.
 */
Route::get('resend-otp/{id}', function() {
    return redirect()->route('home');
})->middleware('auth');

//Handles the resending of the OTP to the user.
Route::post('resend-otp/{id}', [OtpController::class, 'resendOtp'])
    ->whereNumber('id')
    ->name('otp.resend');