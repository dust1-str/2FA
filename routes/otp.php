<?php
// routes/otp.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rutas de verificación OTP
Route::get('otp/{id}', [AuthController::class, 'showOtpForm'])->middleware('logged')->whereNumber('id')->name('otp.form');
Route::post('otp/{id}', [AuthController::class, 'verifyOtp'])->whereNumber('id')->name('otp.verify');
Route::post('resend-otp/{id}', [AuthController::class, 'resendOtp'])->whereNumber('id')->name('otp.resend');
