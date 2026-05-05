<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $divisions = Division::orderBy('name')->get();
        return view('auth.register', compact('divisions'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'division_id'    => ['required', 'exists:divisions,id'],
            'contact_number' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'division_id'    => $validated['division_id'],
            'contact_number' => $validated['contact_number'] ?? null,
            'role'           => User::ROLE_USER,
            'is_active'      => false
        ]);

        $user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Please contact the administrator for the approval account.');
    }
}
