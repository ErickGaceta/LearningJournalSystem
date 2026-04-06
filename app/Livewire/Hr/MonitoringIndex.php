<?php

namespace App\Livewire\Hr;

use App\Models\TrainingModule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MonitoringIndex extends Component
{
    public int $year;

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Loading spinner... -->
            <svg>...</svg>
        </div>
        HTML;
    }

    public function mount()
    {
        $this->year = request()->get('year', now()->year);
    }

    public function render()
    {
        $now     = now();
        $perPage = 5;

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

        foreach ($quarters as $num => &$quarter) {
            $page = (int) request()->get("q{$num}_page", 1);
            $all  = $quarter['modules'];

            $quarter['paginator'] = new LengthAwarePaginator(
                $all->forPage($page, $perPage),
                $all->count(),
                $perPage,
                $page,
                [
                    'path'     => request()->url(),
                    'pageName' => "q{$num}_page",
                    'query'    => request()->except("q{$num}_page"),
                ]
            );
        }
        unset($quarter);

        $oldestYear     = TrainingModule::min(DB::raw('YEAR(datestart)')) ?? now()->year;
        $availableYears = range(now()->year, $oldestYear);

        return view('livewire.hr.monitoring-index', compact('quarters', 'availableYears'));
    }
}