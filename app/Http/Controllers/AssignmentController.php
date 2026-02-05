<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\TrainingModule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AssignmentController extends Controller
{

    public function index(): View
    {
        $assignments = Assignment::with(['user', 'trainingModule'])
            ->latest()
            ->paginate(15);

        return view('pages.assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        $trainingModules = TrainingModule::orderBy('title')->get();

        return view('pages.assignments.create', compact('users', 'trainingModules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_name' => 'required|string|max:1000',
            'user_id' => 'nullable|exists:users,id',
            'training_module' => 'required|string|max:255',
            'module_id' => 'nullable|exists:training_module,id',
        ]);

        Assignment::create($validated);

        return redirect()->route('pages.assignments.index')
            ->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment): View
    {
        $assignment->load(['user', 'trainingModule']);

        return view('pages.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        $users = User::orderBy('name')->get();
        $trainingModules = TrainingModule::orderBy('title')->get();

        return view('pages.assignments.edit', compact('assignment', 'users', 'trainingModules'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'employee_name' => 'required|string|max:1000',
            'user_id' => 'nullable|exists:users,id',
            'training_module' => 'required|string|max:255',
            'module_id' => 'nullable|exists:training_module,id',
        ]);

        $assignment->update($validated);

        return redirect()->route('pages.assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()->route('pages.assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }
}