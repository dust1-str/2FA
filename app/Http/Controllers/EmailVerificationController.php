<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Validator;

/**
 * Class EmailVerificationController
 *
 * This controller handles the email verification process.
 *
 * @package App\Http\Controllers
 */
class EmailVerificationController extends Controller
{
    /**
     * Verify the user's email address.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @param int $id The ID of the user to verify.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function verifyEmail(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            Log::warning('Email verification failed: Email already verified', [
                'user_id' => $user->id,
                'email' => $user->email,
                'IP' => $request->ip(),
                'URL' => $request->url(),
                'CONTROLLER' => EmailVerificationController::class,
                'METHOD' => 'verifyEmail',
            ]);
            return redirect()->route('login.form')->with('message', 'Email already verified.');
        }

        $user->email_verified_at = Carbon::now();
        $user->save();
        Log::info('Email verification successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'IP' => $request->ip(),
            'URL' => $request->url(),
            'CONTROLLER' => EmailVerificationController::class,
            'METHOD' => 'verifyEmail',
        ]);

        return view('auth.email-verified');
    }

    public function showResendVerificationForm()
    {
        return view('auth.resend-verification');
    }

    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        ]);

        if ($validator->fails()) {
            $request->session()->put('passed', false);
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->safe()->only(['email']);

        $user = User::where('email', $data['email'])->first();
        if ($user) {
            event(new UserRegistered($user));
            Log::info('Email verification link sent again', [
                'user_id' => $user->id,
                'email' => $user->email,
                'IP' => $request->ip(),
                'URL' => $request->url(),
                'CONTROLLER' => EmailVerificationController::class,
                'METHOD' => 'resendVerification',
            ]);
            return redirect()->route('login')->with('message', 'Verification email sent. Please check your inbox.');
        } else {
            $request->session()->put('passed', false);
            Log::error('Sending email verification failed: No user found with that email address.', [
                'email' => $data['email'],
                'IP' => $request->ip(),
                'URL' => $request->url(),
                'CONTROLLER' => EmailVerificationController::class,
                'METHOD' => 'resendVerification',
            ]);
            return back()->withErrors([
                'failed' => 'No user found with that email address.',
            ])->withInput();
        }
    }
}
