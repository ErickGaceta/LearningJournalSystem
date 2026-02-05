<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match($user->user_type) {
            'admin' => view('pages.admin.index'),
            'user' => view('pages.users.index'),
            default => abort(403, 'Unauthorized'),
        };
    }
}