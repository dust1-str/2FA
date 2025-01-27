<?php
use Illuminate\Support\Facades\Route;

// Importa las rutas de autenticación
require __DIR__.'/auth.php';

// Importa las rutas de verificación OTP
require __DIR__.'/otp.php';

// Rutas protegidas por autenticación y verificación de correo electrónico
Route::middleware(['auth','verified'])->group(function () {
    // Ruta de inicio que se mostrará cuando haya completado la autenticación
    Route::get('home', function () {
        return view('home');
    })->name('home');
});