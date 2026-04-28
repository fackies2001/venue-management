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
        // This pulls ALL divisions from the database to show in the dropdown
        $divisions = Division::orderBy('name')->get();
        return view('auth.register', compact('divisions'));
    }

    public function register(Request $request)
    {
        // 1. Validate the form inputs
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
            'division_id'    => ['required', 'string'],
            'other_division' => ['required_if:division_id,others', 'string', 'max:255', 'nullable'],
            'contact_number' => ['nullable', 'string', 'max:20'],
        ]);

        $divisionId = $validated['division_id'];

        // ------------------------------------------------------------------
        // THE MAGIC PART: IF THEY SELECTED 'OTHERS' AND TYPED A NEW DIVISION
        // ------------------------------------------------------------------
        if ($divisionId === 'others' && !empty($validated['other_division'])) {
            // firstOrCreate checks if it exists. If not, it adds it to the database!
            $newDivision = Division::firstOrCreate([
                'name' => strtoupper(trim($validated['other_division']))
            ]);

            // Swap out "others" with the actual ID of the newly created division
            $divisionId = $newDivision->id;
        }

        // 2. Create the User and attach the correct division ID
        $user = User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'division_id'    => $divisionId,
            'contact_number' => $validated['contact_number'] ?? null,
            'role'           => User::ROLE_USER,
            'is_active'      => false
        ]);

        // 3. Send email verification
        $user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please check your email to verify your account before logging in.');
    }
}
