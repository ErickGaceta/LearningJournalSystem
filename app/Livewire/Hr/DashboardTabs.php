<?php

namespace App\Livewire\Hr;

use Livewire\Component;
use App\Models\TrainingModule;
use App\Models\Assignment;

class DashboardTabs extends Component
{
    public string $activeTab = 'dashboard';

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Loading spinner... -->
            <svg>...</svg>
        </div>
        HTML;
    }

    public function render()
    {
        $now = now();

        // HR stats
        $totalModules    = TrainingModule::count();
        $activeTraining  = TrainingModule::where('datestart', '<=', $now)->where('dateend', '>=', $now)->count();
        $usersInTraining = Assignment::whereHas('module', fn($q) =>
            $q->where('datestart', '<=', $now)->where('dateend', '>=', $now)
        )->distinct('user_id')->count('user_id');

        // Admin stats
        $activeModules    = TrainingModule::where('datestart', '<=', $now)->where('dateend', '>=', $now)->count();
        $completedModules = TrainingModule::where('dateend', '<', $now)->count();
        $activeAssignments = Assignment::whereHas('module', fn($q) =>
            $q->where('datestart', '<=', $now)->where('dateend', '>=', $now)
        )->distinct('user_id')->count('user_id');

        // Shared
        $modules = TrainingModule::withCount('assignments')->latest()->get();

        return view('livewire.hr.dashboard-tabs', compact(
            'modules',
            'totalModules',
            'activeTraining',
            'usersInTraining',
            'activeModules',
            'completedModules',
            'activeAssignments',
        ));
    }
}