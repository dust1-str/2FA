<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use App\Events\SendOtp;
use App\Rules\Recaptcha;

/**
 * Class OtpController
 *
 * This controller handles the OTP (One-Time Password) verification process.
 *
 * @package App\Http\Controllers
 */
class OtpController extends Controller
{
    /**
     * Show the OTP form.
     *
     * @param int $id The ID of the user.
     * @return \Illuminate\View\View
     */
    public function showOtpForm($id)
    {
        return view('auth.otp', compact('id'));
    }

    /**
     * Verify the OTP.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @param int $id The ID of the user.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyOtp(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|max:6|',
            'g-recaptcha-response' => ['required', new Recaptcha]
        ]);

        if ($validator->fails()) {
            $request->session()->put('passed', false);
            return back()->withErrors($validator)->withInput();
        }

        $otp = $validator->safe()->only('otp');
        $user = User::find($id);
        $otpValidate = (new Otp)->validate($user['email'], $otp['otp']);

        if ($otpValidate->status === true) {
            Log::info('Authentication successful: OTP valid. Redirecting to home', [
                'user_id' => $user->id,
                'email' => $user->email,
                'IP' => $request->ip(),
                'URL' => $request->url(),
                'CONTROLLER' => OtpController::class,
                'METHOD' => 'verifyOtp',
            ]);
            Auth::login($user);
            return redirect()->intended('home');
        }

        /**
         * Set the 'passed' session variable to false to allow the user to retry entering the OTP.
         * The 'LoginPassed' middleware will redirect the user to the login form if this variable exists and is false.
         * The middleware will clear the session variable
         */
        $request->session()->put('passed', false);
        Log::error('Authentication failed: OTP is not valid.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'IP' => $request->ip(),
            'URL' => $request->url(),
            'CONTROLLER' => OtpController::class,
            'METHOD' => 'verifyOtp',
        ]);

        return back()->withErrors([
            'failed' => $otpValidate->message,
        ])->withInput();
    }

    public function resendOtp(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'g-recaptcha-response' => ['required', new Recaptcha]
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $request->session()->put('passed', false);
            return back()->withErrors(['failed' => $errors])->withInput();
        }
        
        $user = User::find($id);

        if ($user) {
            $otp = (new Otp)->generate($user['email'], 'numeric', 6, 2);
            $code = $otp->token;
            event(new SendOtp($user, $code));
            $request->session()->put('passed', false);
            Log::info('Otp code sent successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'IP' => $request->ip(),
                'URL' => $request->url(),
                'CONTROLLER' => OtpController::class,
                'METHOD' => 'resendOtp',
            ]);
            return redirect()->route('otp.form', [$user])->with('success', 'OTP has been resent to your email address.');
        }

        Log::error('Failed to resend OTP', [
            'IP' => $request->ip(),
            'URL' => $request->url(),
            'CONTROLLER' => OtpController::class,
            'METHOD' => 'resendOtp',
        ]);
        return back()->withErrors(['failed' => 'Failed to resend OTP. Please try again.']);
    }
}