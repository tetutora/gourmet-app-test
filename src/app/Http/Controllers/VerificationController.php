<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        User::verifyEmail($request);
        return redirect()->route('index')->with('verified', true);
    }

    public function resend(Request $request)
    {
        Auth::user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
}
