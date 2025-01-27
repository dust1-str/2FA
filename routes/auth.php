<?php
// routes/auth.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    // Rutas de autenticación
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    // Rutas de registro
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

// Ruta de cierre de sesión
Route::middleware('auth')->group(function () {
    /**
     * La siguiente ruta GET es para evitar que la aplicación falle si se accede a '/logout' desde el navegador.
     * La ruta POST '/logout' debe ser utilizada únicamente desde el botón que se encarga de realizar la acción de cierre de sesión.
     * Acceder a esta ruta mediante GET redirige al usuario a la página de inicio.
     */
    Route::get('logout', function() {
        return redirect()->route('home');
    });
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Verifica el correo electrónico cuando el usuario da clic en el enlace que se envía a su correo
Route::get('/email/verify/{id}', [EmailVerificationController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');