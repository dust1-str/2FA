<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Events\UserRegistered;
use App\Events\SendOtp;
use Illuminate\Support\Carbon;
use Ichtrojan\Otp\Otp;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function showOtpForm($id)
    {
        return view('auth.otp', compact('id'));
    }

    public function verifyEmail(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            return redirect()->route('login.form')->with('message', 'Email already verified.');
        }

        $user->email_verified_at = Carbon::now();
        $user->save();

        return view('auth.email-verified');
    }

    public function resendOtp(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $otp = (new Otp)->generate($user['email'], 'numeric', 6, 2);
            $code = $otp->token;
            event(new SendOtp($user, $code));
            $request->session()->put('otp_passed', false);
            return redirect()->route('otp.form', [$user])->with('success', 'OTP has been resent to your email address.');
        }

        return back()->withErrors(['failed' => 'Failed to resend OTP. Please try again.']);
    }

    public function verifyOtp(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $otp = $validator->safe()->only('otp');
        $user = User::find($id);
        $otpValidate = (new Otp)->validate($user['email'], $otp['otp']);

        if ($otpValidate->status === true) {
            Auth::login($user);
            return redirect()->intended('home');
        }

        // Establecer el indicador en la sesión
        $request->session()->put('otp_passed', false);

        return back()->withErrors([
            'failed' => $otpValidate->message,
        ])->withInput();

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
        if ($user) {
            if ($user->email_verified_at === null) {
                return back()->withErrors([
                    'failed' => 'Your account is not verified. Please check your email inbox.',
                ])->withInput();
            }

            if ($user['email'] === $credentials['email'] && Hash::check($credentials['password'], $user['password'])) {
                $otp = (new Otp)->generate($credentials['email'], 'numeric', 6, 2);
                $code = $otp->token;
                event(new SendOtp($user, $code));
                return redirect()->route('otp.form', [$user]);
            }
        }

        return back()->withErrors([
            'failed' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|regex:/^[\pL\s]+$/u',
            'email_local' => 'required|string|max:255',
            'email_domain' => 'required|in:gmail.com,hotmail.com,outlook.com',
            'password' => 'required|min:8|max:12',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->input('email_local') . '@' . $request->input('email_domain');

        $data = $validator->safe()->except('confirm_password');
        $data['email'] = $email;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($user) {
            event(new UserRegistered($user));
        }

        return redirect()->route('register')->with('success', 'We have sent you an email to verify your account. Please check your inbox.');
    }
}