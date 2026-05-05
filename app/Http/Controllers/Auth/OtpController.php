<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginOtpMail;

class OtpController extends Controller
{
    public function index()
    {
        // Check if user has passed the first login step
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric|digits:6']);

        $user = User::find(session('2fa_user_id'));

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        // Check if OTP matches and is not expired
        if ($user->otp_code == $request->otp_code) {

            // Clear OTP and authenticate user
            $user->update(['otp_code' => null, 'otp_expires_at' => null]);
            Auth::login($user);
            session()->forget('2fa_user_id');

            // Route based on role
            $prefix = match ($user->role) {
                'admin'       => 'admin',
                'super_admin' => 'super-admin',
                default       => 'user',
            };
            return redirect()->route($prefix . '.dashboard');
        }

        // KUNG MALI ANG OTP: Ibalik sa login page at ipakita ulit ang OTP form
        return back()->with('show_otp', true)->with('error', 'Invalid or expired OTP code.');
    }

    public function resend()
    {
        $user = User::find(session('2fa_user_id'));
        if (!$user) return redirect()->route('login');

        // Generate new OTP
        $otp = rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($otp));

        // Ibalik sa login page at ipakita ulit ang OTP form
        return back()->with('show_otp', true)->with('success', 'A new OTP has been sent to your email.');
    }
}
