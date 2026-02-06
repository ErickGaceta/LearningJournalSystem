<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('pages.auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 1. Get the current user ID and object
        $userId = Auth::id();
        $user = Auth::user();

        // 2. Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        // 3. Update using DB::table to ensure the database bypasses any model logic conflicts
        // We hash manually here because we are bypassing the Eloquent Model
        DB::table('users')
            ->where('id', $userId)
            ->update(['password' => Hash::make($request->password)]);

        // 4. THE FIX: Kill the session and force a save
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // This line forces Laravel to write the 'logged out' state to the session 
        // driver BEFORE the redirect header is sent to the browser.
        $request->session()->save();

        // 5. Redirect to '/' (Welcome) instead of '/login' to break the middleware loop
        return redirect('/')->with('success', 'Password updated! Please login with your new credentials.');
    }
}
