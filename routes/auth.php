<?php
// routes/auth.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\AuthController;

// Rutas de autenticación
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');

// Rutas de registro
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('register', [AuthController::class, 'register'])->name('register');

// Ruta de cierre de sesión
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Verifica el correo electrónico cuando el usuario da clic en el enlace que se envía a su correo
Route::get('/email/verify/{id}', [EmailVerificationController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');