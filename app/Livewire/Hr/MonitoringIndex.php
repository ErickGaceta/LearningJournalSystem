<?php

namespace App\Livewire\Hr;

use App\Models\TrainingModule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy] class MonitoringIndex extends Component
{
    public int $year;

    public function placeholder()
    {
        return <<<'HTML'
    <div class="flex flex-col gap-4 animate-pulse">

        {{-- Header: Year Selector Skeleton --}}
        <div class="flex items-center justify-between">
            <div class="h-5 bg-zinc-100 dark:bg-zinc-800 rounded w-32"></div>
            <div class="h-9 bg-zinc-100 dark:bg-zinc-800 rounded-lg w-28"></div>
        </div>

        {{-- Quarter Tabs Skeleton --}}
        <div class="flex gap-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl p-1 w-fit">
            <div class="h-8 w-24 bg-white dark:bg-zinc-700 rounded-lg"></div>
            <div class="h-8 w-24 bg-zinc-200 dark:bg-zinc-600 rounded-lg opacity-50"></div>
            <div class="h-8 w-24 bg-zinc-200 dark:bg-zinc-600 rounded-lg opacity-50"></div>
            <div class="h-8 w-24 bg-zinc-200 dark:bg-zinc-600 rounded-lg opacity-50"></div>
        </div>

        {{-- Table Skeleton --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            {{-- Table Header --}}
            <div class="grid grid-cols-5 gap-4 px-4 py-3 bg-zinc-50 dark:bg-zinc-800">
                <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded col-span-2"></div>
                <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded mx-auto w-2/3"></div>
                <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded ml-auto w-1/2"></div>
            </div>
            {{-- Table Rows --}}
            @foreach(range(1, 5) as $i)
            <div class="grid grid-cols-5 gap-4 px-4 py-4 border-t border-zinc-100 dark:border-zinc-700">
                <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded col-span-2 w-4/5"></div>
                <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-3/4"></div>
                <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-2/3 mx-auto"></div>
                <div class="h-5 bg-zinc-100 dark:bg-zinc-800 rounded-full w-16 ml-auto"></div>
            </div>
            @endforeach
        </div>

        {{-- Pagination Skeleton --}}
        <div class="flex justify-end gap-2 mt-2">
            <div class="h-8 w-8 bg-zinc-100 dark:bg-zinc-800 rounded-lg"></div>
            <div class="h-8 w-8 bg-zinc-100 dark:bg-zinc-800 rounded-lg"></div>
            <div class="h-8 w-8 bg-zinc-100 dark:bg-zinc-800 rounded-lg"></div>
        </div>

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
