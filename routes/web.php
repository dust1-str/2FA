<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Página de bienvenida
Route::get('/', function () {
    redirect()->route('login.form');
});

// Rutas de autenticación
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');

// Rutas de registro
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('register', [AuthController::class, 'register'])->name('register');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Ruta de inicio
    Route::get('home', function () {
        return view('home');
    })->middleware('verified')->name('home');

    // Ruta de cierre de sesión
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Rutas de verificación OTP
Route::get('otp/{id}', [AuthController::class, 'showOtpForm'])->middleware('logged')->whereNumber('id')->name('otp.form');
Route::post('otp/{id}', [AuthController::class, 'verifyOtp'])->whereNumber('id')->name('otp.verify');
Route::post('resend-otp/{id}', [AuthController::class, 'resendOtp'])->name('otp.resend');

// Verifica el correo electrónico cuando el usuario da clic en el enlace que se envía a su correo
Route::get('/email/verify/{id}', [AuthController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');
