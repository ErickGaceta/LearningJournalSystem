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

        $totalModules = TrainingModule::count();

        $activeTraining = TrainingModule::where('datestart', '<=', $now)
            ->where('dateend', '>=', $now)
            ->count();

        $usersInTraining = Assignment::whereHas(
            'module',
            fn($q) =>
            $q->where('datestart', '<=', $now)->where('dateend', '>=', $now)
        )->distinct('user_id')->count('user_id');

        $modules = TrainingModule::withCount('assignments')->latest()->get();

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

        $assignee = TrainingModule::withCount('assignments')
            ->with('assignments:id,module_id,user_id,employee_name')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('venue', 'like', "%{$search}%")
                        ->orWhere('conductedby', 'like', "%{$search}%")
                        ->orWhereHas(
                            'assignments',
                            fn($q) =>
                            $q->where('employee_name', 'like', "%{$search}%")
                        );
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $moduleIds = $assignee->pluck('id');
        $assignments = Assignment::with('module:id,title')
            ->whereIn('module_id', $moduleIds)
            ->get();

        $users = User::where('user_type', 'user')
            ->select('id', 'first_name', 'last_name')
            ->orderBy('last_name')
            ->get();

        return view('pages.hr.modules.index', compact('assignee', 'users', 'assignments'));
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

        return redirect()->route('hr.monitoring.index')
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

        return redirect()->route('hr.monitoring.index')
            ->with('success', 'Training module updated successfully.');
    }

    public function destroyModule(TrainingModule $module): RedirectResponse
    {
        $module->delete();

        return redirect()->route('hr.monitoring.index')
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

        $users = User::whereIn('id', $validated['user_ids'])
            ->select('id', 'first_name', 'last_name')
            ->get()
            ->keyBy('id');

        $existing = Assignment::where('module_id', $module->id)
            ->whereIn('user_id', $validated['user_ids'])
            ->pluck('user_id')
            ->flip();

        $inserts = [];
        $now = now();

        foreach ($validated['user_ids'] as $userId) {
            if ($existing->has($userId)) continue;

            $inserts[] = [
                'user_id'         => $userId,
                'module_id'       => $module->id,
                'employee_name'   => $users[$userId]->full_name,
                'training_module' => $module->title,
                'status'          => 'assigned',
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        if (!empty($inserts)) {
            Assignment::insert($inserts);
        }

        return redirect()->route('hr.monitoring.index')
            ->with('success', 'Training assigned successfully.');
    }

    public function monitoringIndex(){
        return view('pages.hr.monitoring.index');
    }

    public function destroyAssignment(Assignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()->route('hr.monitoring.index')
            ->with('success', 'Assignment removed successfully.');
    }
}
