<?php

namespace App\Livewire\Hr;

use App\Models\TrainingModule;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MonitoringIndex extends Component
{
    public int $year;

    public function mount()
    {
        $this->year = request()->get('year', now()->year);
    }

    public function render()
    {
        $now = now();

        $allModules = TrainingModule::with([
            'assignments.user.position',
            'documents',
        ])
            ->whereYear('datestart', $this->year)
            ->orderBy('datestart')
            ->get()
            ->each(function ($module) use ($now) {
                $module->status = match (true) {
                    $module->datestart > $now => 'upcoming',
                    $module->dateend   < $now => 'completed',
                    default                   => 'ongoing',
                };
                $module->documentsByUser = $module->documents->keyBy('user_id');
            });

        $quarters = [
            1 => ['label' => 'Quarter 1', 'range' => 'Jan - Mar', 'modules' => collect()],
            2 => ['label' => 'Quarter 2', 'range' => 'Apr - Jun', 'modules' => collect()],
            3 => ['label' => 'Quarter 3', 'range' => 'Jul - Sep', 'modules' => collect()],
            4 => ['label' => 'Quarter 4', 'range' => 'Oct - Dec', 'modules' => collect()],
        ];

        foreach ($allModules as $module) {
            $q = (int) ceil($module->datestart->month / 3);
            $quarters[$q]['modules']->push($module);
        }

        $oldestYear     = TrainingModule::min(DB::raw('YEAR(datestart)')) ?? now()->year;
        $availableYears = range(now()->year, $oldestYear);

        return view('livewire.hr.monitoring-index', compact('quarters', 'availableYears'));
    }
}