<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        // If already authenticated, redirect immediately
        if (Auth::check()) {
            return $this->redirectByUserType(Auth::user());
        }

        $request->validate([
            'login'    => ['required'],
            'password' => ['required'],
        ]);

        // Rate limiting: max 5 attempts per minute per login+IP combo
        $throttleKey = Str::lower($request->input('login')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'login' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('login');
        }

        $loginField  = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginField => $request->input('login'),
            'password'  => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user         = Auth::user();
            $isFirstLogin = is_null($user->last_login);

            $user->last_login = now();
            $user->save();

            if ($isFirstLogin) {
                // Set a session flag — middleware will enforce this
                $request->session()->put('must_change_password', true);

                return redirect()->route('password.change.show')
                    ->with('info', 'Welcome! Please change your password to continue.');
            }

            return $this->redirectByUserType($user);
        }

        RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    private function redirectByUserType($user)
    {
        return match ($user->user_type) {
            'admin' => redirect()->route('admin.dashboard'),
            'hr'    => redirect()->route('hr.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }
}