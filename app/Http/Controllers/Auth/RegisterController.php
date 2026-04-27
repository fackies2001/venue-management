<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'department'     => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'department'     => $validated['department'],
            'contact_number' => $validated['contact_number'] ?? null,
            'role'           => User::ROLE_USER,
            'is_active'      => true, // ← active agad, email verify na lang
        ]);

        // Send verification email
        $user->sendEmailVerificationNotification();

        // Auto-login then redirect to verify page
        auth()->login($user);

        return redirect()->route('verification.notice');
    }
}
