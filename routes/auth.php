<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\AuthController;

/**
 * Routes for guests (unauthenticated users).
 * These routes handle user authentication and registration.
 */
Route::middleware('guest')->group(function () {
    //Show the login form.
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');

    //Handle a login request.
    Route::post('login', [AuthController::class, 'login'])->name('login');

    //Show the registration form.
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.form');

    //Handle a registration request.
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

/**
 * Routes for authenticated users.
 * These routes handle user logout and email verification.
 */
Route::middleware('auth')->group(function () {
    /**
     * Redirect to home if accessing '/logout' via GET.
     * The POST '/logout' route should be used for logging out.
     */
    Route::get('logout', function() {
        return redirect()->route('home');
    });

    //Handle a logout request.
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

/**
 * Verify the user's email address.
 * This route is accessed when the user clicks the verification link sent to their email.
 * Protected by the 'signed' that verifies the signature of the URL.
 *
     * @param int $id The ID of the user to verify.
 */
Route::get('/email/verify/{id}', [EmailVerificationController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');