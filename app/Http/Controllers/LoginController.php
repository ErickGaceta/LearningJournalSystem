<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->input('login'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if this is the first login
            $isFirstLogin = is_null($user->last_login) || 
                           $user->created_at->eq($user->updated_at);

            // Update last_login
            $user->last_login = now();
            $user->save();

            // If first login, redirect to change password
            if ($isFirstLogin) {
                return redirect()->route('password.change')
                    ->with('info', 'Welcome! Please change your password for security.');
            }

            // Redirect based on user_type
            return match ($user->user_type) {
                'admin' => redirect()->route('admin.dashboard'),
                'hr' => redirect()->route('hr.dashboard'),
                'user' => redirect()->route('user.dashboard'),
                default => redirect()->route('user.dashboard'),
            };
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }
}