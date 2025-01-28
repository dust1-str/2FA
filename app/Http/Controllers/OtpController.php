<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\SendOtp;

class OtpController extends Controller
{
    public function showOtpForm($id)
    {
        
        return view('auth.otp', compact('id'));
    }

    public function verifyOtp(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'otp' => 'required|max:6',
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
}
