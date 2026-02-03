<?php

namespace App\Http\Controllers;

use App\Models\DivisionUnit;
use Illuminate\Http\Request;

class DivisionsController extends Controller
{
    public function index()
    {
        $divisions = DivisionUnit::latest()->paginate(15);
        return view('pages.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('pages.divisions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_units' => 'required|string|max:255|unique:division_units,division_units',
        ]);

        DivisionUnit::create($validated);

        return redirect()->route('divisions.index')->with('success', 'Division/Unit created successfully.');
    }

    public function show(DivisionUnit $division)
    {
        return view('pages.divisions.show', compact('division'));
    }

    public function edit(DivisionUnit $division)
    {
        return view('pages.divisions.edit', compact('division'));
    }

    public function update(Request $request, DivisionUnit $division)
    {
        $validated = $request->validate([
            'division_units' => 'required|string|max:255|unique:division_units,division_units,' . $division->id,
        ]);

        $division->update($validated);

        return redirect()->route('divisions.index')->with('success', 'Division/Unit updated successfully.');
    }

    public function destroy(DivisionUnit $division)
    {
        // Check if division is being used by any users
        if ($division->users()->count() > 0) {
            return redirect()->route('divisions.index')
                ->with('error', 'Cannot delete division/unit. It is currently assigned to ' . $division->users()->count() . ' user(s).');
        }

        $division->delete();

        return redirect()->route('divisions.index')->with('success', 'Division/Unit deleted successfully.');
    }
}