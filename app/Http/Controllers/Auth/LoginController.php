<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // 1. Verify standard credentials first
        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {

            // 2. Original Security Checks
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            if (!$user->is_active) {
                return redirect()->route('login')
                    ->with('error', 'Your account has been deactivated. Please contact the administrator.');
            }

            if ($user->isPending()) {
                return redirect()->route('approval.pending');
            }

            if ($user->isRejected()) {
                return redirect()->route('login')
                    ->with('error', 'Your account has been rejected. Please contact the administrator.');
            }

            // 3. Generate 6-digit OTP
            $otp = rand(100000, 999999);

            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(10)
            ]);

            // 4. Send OTP Email
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($otp));

            // 5. Store user ID in session
            session(['2fa_user_id' => $user->id]);

            // 6. Return back to login page with the 'show_otp' flag
            return back()->with('show_otp', true)->with('success', 'OTP sent to your email.');
        }

        return back()->withErrors(['email' => 'Invalid credentials provided.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function approvalPending()
    {
        return view('auth.approval-pending');
    }
}
