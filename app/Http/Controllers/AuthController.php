<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Events\UserRegistered;
use App\Events\SendOtp;
use Ichtrojan\Otp\Otp;
use App\Rules\Recaptcha;
use Http;

/**
 * Class AuthController
 *
 * This controller handles the authentication and registration of users.
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|max:12',
            'g-recaptcha-response' => ['required', new Recaptcha]
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $validator->safe()->only(['email', 'password']);

        $user = User::where('email', $credentials['email'])->first();
        if ($user) {
            if ($user->email_verified_at === null) {
                // Clear previous errors and add new error
                Log::warning('Login attempt failed: email not verified', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'IP' => $request->ip(),
                    'URL' => $request->url(),
                    'CONTROLLER' => AuthController::class,
                    'METHOD' => 'login',
                ]);
                $request->session()->forget('errors');
                return back()->withErrors([
                    'verified' => 'Your account is not verified. Please check your email inbox.',
                ])->withInput();
            }

            if (Hash::check($credentials['password'], $user['password'])) {
                $otp = (new Otp)->generate($credentials['email'], 'numeric', 6, 2);
                $code = $otp->token;
                event(new SendOtp($user, $code));
                Log::info('Login successful. OTP sent', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'IP' => $request->ip(),
                    'URL' => $request->url(),
                    'CONTROLLER' => AuthController::class,
                    'METHOD' => 'login',
                ]);
                return redirect()->route('otp.form', [$user]);
            }
        }

        Log::error('Login attempt failed: invalid credentials', [
            'email' => $credentials['email'],
            'IP' => $request->ip(),
            'URL' => $request->url(),
            'CONTROLLER' => AuthController::class,
            'METHOD' => 'login',
        ]);
        // Clear previous errors and add new error
        $request->session()->forget('errors');
        return back()->withErrors([
            'failed' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Show the register form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|regex:/^[\pL\s]+$/u',
            'email' => 'required|unique:users|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => 'required|min:8|max:12',
            'confirm_password' => 'required|same:password',
            'g-recaptcha-response' => ['required', new Recaptcha]
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->safe()->except('confirm_password', 'g-recaptcha-response');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($user) {
            event(new UserRegistered($user));
            Log::info('New user created. Email verification link sent', [
                'user_id' => $user->id,
                'email' => $user->email,
                'IP' => $request->ip(),
                'URL' => $request->url(),
                'CONTROLLER' => AuthController::class,
                'METHOD' => 'register',
            ]);
            return back()->with('success', 'We have sent you an email to verify your account. Please check your inbox.');
        } else {
            Log::error('Failed to create new user', [
                'IP' => $request->ip(),
                'URL' => $request->url(),
                'CONTROLLER' => AuthController::class,
                'METHOD' => 'register',
            ]);
            return back()->withErrors([
                'failed' => 'Failed to create new user. Please try again.',
            ])->withInput();
        }
    }

    //Handle a logout request.
    public function logout(Request $request)
    {
        Log::info('User logging out', [
            'USER' => $request->user()->email,
            'IP' => $request->ip(),
            'URL' => $request->url(),
            'CONTROLLER' => AuthController::class,
            'METHOD' => 'logout',
        ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}