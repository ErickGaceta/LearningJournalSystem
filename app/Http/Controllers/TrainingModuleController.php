<?php

namespace App\Http\Controllers;

use App\Models\TrainingModule;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TrainingModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $trainingModules = TrainingModule::withCount('assignments')
            ->latest()
            ->paginate(15);

        return view('training-modules.index', compact('trainingModules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('training-modules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:1000',
            'hours' => 'required|string|max:1000',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:100',
            'registration_fee' => 'required|string|max:100',
            'travel_expenses' => 'required|string|max:100',
        ]);

        TrainingModule::create($validated);

        return redirect()->route('training-modules.index')
            ->with('success', 'Training module created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingModule $trainingModule): View
    {
        $trainingModule->load('assignments.user');

        return view('training-modules.show', compact('trainingModule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingModule $trainingModule): View
    {
        return view('training-modules.edit', compact('trainingModule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingModule $trainingModule): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:1000',
            'hours' => 'required|string|max:1000',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:100',
            'registration_fee' => 'required|string|max:100',
            'travel_expenses' => 'required|string|max:100',
        ]);

        $trainingModule->update($validated);

        return redirect()->route('training-modules.index')
            ->with('success', 'Training module updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingModule $trainingModule): RedirectResponse
    {
        $trainingModule->delete();

        return redirect()->route('training-modules.index')
            ->with('success', 'Training module deleted successfully.');
    }
}