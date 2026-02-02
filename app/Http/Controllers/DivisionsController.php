<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DivisionsController extends Controller
{
    public function divisions()
    {
        // Logic to retrieve and display divisions
        return view('pages.divisions');
    }
}
