<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function create()
    {
        return view('pages.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login'    => ['required'],
            'password' => ['required'],
        ]);

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

            $user->updateQuietly(['last_login' => now()]);

            if ($isFirstLogin) {
                $request->session()->put('must_change_password', true);

                return redirect()->route('password.change.show')
                    ->with('info', 'Welcome! Please change your password to continue.');
            }

            return $this->redirectByUserType($user->user_type);
        }

        RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByUserType(string $userType): RedirectResponse
    {
        return match ($userType) {
            'admin' => redirect()->route('admin.dashboard'),
            'hr'    => redirect()->route('hr.dashboard'),
            default => redirect()->route('user.dashboard'),
        };
    }
}