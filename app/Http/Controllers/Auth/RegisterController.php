<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password as PasswordRule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->letters()->numbers()->mixedCase(),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        // Generate an OTP verification record for the phone
        try {
            \App\Models\Verification::create([
                'phone' => $validated['phone'],
                'code' => rand(100000, 999999),
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
            ]);
        } catch (\Throwable $e) {
            // don't block registration on OTP persistence failure; log and continue
            \Log::error('Failed to create verification record: ' . $e->getMessage());
        }

        // Log in the user but require phone verification before accessing account
        Auth::login($user);

        return redirect()->route('verify.show')->with('phone', $validated['phone']);
    }
}

