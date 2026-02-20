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
        $now = now();

        $modules = TrainingModule::with('assignments')->latest()->get();

        $totalModules = $modules->count();

        $activeTraining = $modules->filter(
            fn($m) => $now->between($m->datestart, $m->dateend)
        )->count();

        $usersInTraining = Assignment::whereHas('module', function ($q) use ($now) {
            $q->where('datestart', '<=', $now)->where('dateend', '>=', $now);
        })->distinct('user_id')->count('user_id');

        return view('pages.hr.dashboard', compact(
            'modules',
            'totalModules',
            'activeTraining',
            'usersInTraining'
        ));
    }

    // ========== Training Module Management ==========
    public function modulesIndex(Request $request)
    {
        $search = $request->get('search');

        $trainingModules = TrainingModule::withCount('assignments')
            ->with('assignments:id,module_id,user_id,employee_name')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('conductedby', 'like', "%{$search}%")
                    ->orWhereHas('assignments', function ($q) use ($search) {
                        $q->where('employee_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $assignments = Assignment::with('module')->get();

        $users = User::where('user_type', 'user')
            ->orderBy('last_name')
            ->get();

        return view('pages.hr.modules.index', compact('trainingModules', 'users', 'assignments'));
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
    public function storeAssignment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'required|exists:users,id',
            'module_id'  => 'required|exists:training_module,id',
        ]);

        $module = TrainingModule::findOrFail($validated['module_id']);

        foreach ($validated['user_ids'] as $userId) {
            $user = User::findOrFail($userId);

            Assignment::firstOrCreate(
                ['user_id' => $userId, 'module_id' => $module->id],
                [
                    'employee_name'   => $user->full_name,
                    'training_module' => $module->title,
                    'status'          => 'assigned',
                ]
            );
        }

        return redirect()->route('hr.modules.index')
            ->with('success', 'Training assigned successfully.');
    }

    public function destroyAssignment(Assignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()->route('hr.modules.index')
            ->with('success', 'Assignment removed successfully.');
    }
}
