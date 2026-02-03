<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionsController extends Controller
{
    public function index()
    {
        $positions = Position::latest()->paginate(15);
        return view('pages.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('pages.positions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'positions' => 'required|string|max:255|unique:positions,positions',
        ]);

        Position::create($validated);

        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    public function show(Position $position)
    {
        return view('pages.positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        return view('pages.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'positions' => 'required|string|max:255|unique:positions,positions,' . $position->id,
        ]);

        $position->update($validated);

        return redirect()->route('positions.index')->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        // Check if position is being used by any users
        if ($position->users()->count() > 0) {
            return redirect()->route('positions.index')
                ->with('error', 'Cannot delete position. It is currently assigned to ' . $position->users()->count() . ' user(s).');
        }

        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully.');
    }
}
