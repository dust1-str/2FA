<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function verifiyOtp(){

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|max:12',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $validator->safe()->only(['email', 'password']);

        $user = User::where('email', $credentials['email'])->first();
        if ($user->email_verified_at === null) {
            return back()->withErrors([
                'failed' => 'Your account is not verified. Please check your email inbox.',
            ])->withInput();
        }

        if ($user['email'] === $credentials['email'] && Hash::check($credentials['password'], $user['password'])) {
            return redirect()->route('otp');
        }

        return back()->withErrors([
            'failed' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:12',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->safe()->except('confirm_password');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($user) {
            Auth::login($user);
            event(new Registered($user));
        }

        return redirect()->route('register')->with('success', 'We have sent you an email to verify your account. Please check your inbox.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
