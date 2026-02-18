<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        if (
            Auth::check() &&
            $request->session()->get('must_change_password') &&
            !$request->routeIs('password.change.*') &&
            !$request->routeIs('logout')
        ) {
            return redirect()->route('password.change.show')
                ->with('info', 'You must change your password before continuing.');
        }

        return $next($request);
    }
}