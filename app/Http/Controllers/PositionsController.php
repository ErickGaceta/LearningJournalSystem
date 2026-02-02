<?php

namespace App\Http\Controllers;

use App\Models\Position;

use Illuminate\Http\Request;

class PositionsController extends Controller
{
    public function index()
    {
        return view('pages.positions.index');
    }

    public function create()
    {
        return view('pages.positions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'positions' => 'required|string|max:255',
        ]);

        \App\Models\Position::create($validated);

        return redirect()->route('positions')->with('success', 'Position created successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return redirect()->route('positions')->with('success', 'Position deleted successfully.');
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'positions' => 'required|string|max:255',
        ]);

        $position->update($validated);

        return redirect()->route('positions')->with('success', 'Position updated successfully.');
    }
}
