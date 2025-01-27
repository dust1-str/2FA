<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;

class EmailVerificationController extends Controller
{
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
