<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\TrainingModule;
use App\Models\Assignment;
use App\Models\User;

class HRController extends Controller
{
    // ========== Dashboard ==========
    public function dashboard()
    {
        $totalModules = TrainingModule::count();
        $activeTraining = Assignment::where('status', 'ongoing')->count('module_id');
        $usersInTraining = Assignment::where('status', 'ongoing')->count('user_id');


        return view('pages.hr.dashboard', compact(
            'totalModules',
            'activeTraining',
            'usersInTraining'
        ));
    }

    // ========== Training Module Management ==========
    public function modulesIndex()
    {
        $trainingModules = TrainingModule::withCount('assignments')
            ->latest()
            ->paginate(15);

        return view('pages.hr.modules.index', compact('trainingModules'));
    }

    public function createModule()
    {
        return view('pages.hr.modules.create');
    }

    public function storeModule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:1000',
            'hours' => 'required|string|max:1000',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:100',
            'registration_fee' => 'string|max:100',
        ]);

        TrainingModule::create($validated);

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training module created successfully.');
    }

    public function editModule(TrainingModule $module)
    {
        return view('pages.hr.modules.edit', compact('module'));
    }

    public function updateModule(Request $request, TrainingModule $module): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:1000',
            'hours' => 'required|string|max:1000',
            'datestart' => 'required|date',
            'dateend' => 'required|date|after_or_equal:datestart',
            'venue' => 'required|string|max:255',
            'conductedby' => 'required|string|max:100',
            'registration_fee' => 'string|max:100',
        ]);

        $module->update($validated);

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training module updated successfully.');
    }

    public function destroyModule(TrainingModule $module): RedirectResponse
    {
        $module->delete();

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training module deleted successfully.');
    }

    // ========== Assignment Management ==========
    public function assignmentsIndex()
    {
        $assignments = Assignment::with(['user', 'module'])
            ->latest()
            ->paginate(15);

        return view('pages.hr.assignments.index', compact('assignments'));
    }

    public function createAssignment()
    {
        $users = User::where('user_type', 'user')->get();

        // Only show modules that have not yet ended (or have no end date)
        $modules = TrainingModule::where(function ($query) {
            $query->whereNull('dateend')
                ->orWhere('dateend', '>=', now());
        })->get();

        return view('pages.hr.assignments.create', compact('users', 'modules'));
    }

    public function storeAssignment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'required|exists:users,id',
            'module_id'  => 'required|exists:training_module,id',
        ]);

        $module = TrainingModule::findOrFail($validated['module_id']);

        if ($module->date_end && now()->gte($module->date_end)) {
            return back()->withErrors(['module_id' => 'This training module has already ended.']);
        }

        foreach ($validated['user_ids'] as $userId) {
            $user = User::findOrFail($userId);

            Assignment::create([
                'user_id'         => $userId,
                'module_id'       => $module->id,
                'employee_name'   => $user->full_name,
                'training_module' => $module->title,
                'status'          => 'assigned',
            ]);
        }

        return redirect()->route('hr.assignments.index')
            ->with('success', 'Training module assigned successfully.');
    }

    public function destroyAssignment(Assignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()->route('hr.assignments.index')
            ->with('success', 'Assignment removed successfully.');
    }
}
