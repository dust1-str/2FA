<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;

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
}
