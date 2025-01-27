<?php
// routes/otp.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;

// Rutas de verificación OTP
Route::get('otp/{id}', [OtpController::class, 'showOtpForm'])->middleware('logged')->whereNumber('id')->name('otp.form');
Route::post('otp/{id}', [OtpController::class, 'verifyOtp'])->whereNumber('id')->name('otp.verify');
Route::post('resend-otp/{id}', [OtpController::class, 'resendOtp'])->whereNumber('id')->name('otp.resend');
