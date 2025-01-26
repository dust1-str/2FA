<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm']);
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::get('register', [LoginController::class, 'showRegisterForm']);
Route::post('register', [LoginController::class, 'register'])->name('register');

Route::middleware(['logged'])->group(function () {
    Route::get('otp/{id}', [LoginController::class, 'showOtpForm'])->whereNumber('id')->name('otp');
});

Route::post('otp/{id}', [LoginController::class, 'verifyOtp'])->whereNumber('id')->name('verifyOtp');
Route::post('resend-otp', [LoginController::class, 'resendOtp'])->name('resendOtp');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('home', function () {
    return view('home');
})->middleware(['auth','verified']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return view('auth.email-verified');
})->middleware(['auth','signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
