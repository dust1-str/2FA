<?php
// routes/otp.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;

// Rutas de verificación OTP
Route::get('otp/{id}', [OtpController::class, 'showOtpForm'])->middleware('logged')->whereNumber('id')->name('otp.form');
Route::post('otp/{id}', [OtpController::class, 'verifyOtp'])->whereNumber('id')->name('otp.verify');

/**
 * La siguiente ruta GET es para evitar que la aplicación falle si se accede a '/resend-otp/{id}' desde el navegador.
 * La ruta POST '/resend-otp/{id}' debe ser utilizada únicamente desde el formulario de verificación.
 * Acceder a esta ruta mediante GET redirige al usuario a la página de inicio.
 */
Route::get('resend-otp/{id}', function() {
    return redirect()->route('home');
})->middleware('auth');
Route::post('resend-otp/{id}', [OtpController::class, 'resendOtp'])->whereNumber('id')->name('otp.resend');
