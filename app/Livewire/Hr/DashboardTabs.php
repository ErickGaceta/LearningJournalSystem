<?php

namespace App\Livewire\Hr;

use Livewire\Component;
use App\Models\TrainingModule;
use App\Models\Assignment;use Livewire\Attributes\Lazy;

#[Lazy] class DashboardTabs extends Component
{
    public string $activeTab = 'dashboard';

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl animate-pulse">

            {{-- Stats Cards Skeleton --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="h-24 bg-zinc-100 dark:bg-zinc-800 rounded-xl"></div>
                <div class="h-24 bg-zinc-100 dark:bg-zinc-800 rounded-xl"></div>
                <div class="h-24 bg-zinc-100 dark:bg-zinc-800 rounded-xl"></div>
            </div>

            {{-- Table Skeleton --}}
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                {{-- Header --}}
                <div class="grid grid-cols-4 gap-4 px-4 py-3 bg-zinc-50 dark:bg-zinc-800">
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                </div>
                {{-- Rows --}}
                @foreach(range(1, 5) as $i)
                <div class="grid grid-cols-4 gap-4 px-4 py-4 border-t border-zinc-100 dark:border-zinc-700">
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-3/4"></div>
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-1/2"></div>
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-2/3 mx-auto"></div>
                    <div class="h-5 bg-zinc-100 dark:bg-zinc-800 rounded-full w-16 ml-auto"></div>
                </div>
                @endforeach
            </div>

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