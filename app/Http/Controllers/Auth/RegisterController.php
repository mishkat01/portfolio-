<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        // Check if registration is enabled
        $registrationEnabled = Setting::where('key', 'user_registration')->value('value');
        if ($registrationEnabled === '0') {
             abort(403, 'Registration is currently disabled.');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Check if registration is enabled
        $registrationEnabled = Setting::where('key', 'user_registration')->value('value');
        if ($registrationEnabled === '0') {
             abort(403, 'Registration is currently disabled.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Check if email verification is required
        $verificationRequired = Setting::where('key', 'email_verification')->value('value');
        
        if ($verificationRequired === '1') {
            event(new Registered($user));
            Auth::login($user);
            return redirect()->route('verification.notice');
        } else {
            // Mark email as verified if not required
            $user->markEmailAsVerified();
            Auth::login($user);
            return redirect()->intended('dashboard');
        }
    }
}
