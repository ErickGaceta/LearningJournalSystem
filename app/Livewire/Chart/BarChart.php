<?php

namespace App\Livewire\Chart;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\TrainingModule;
use App\Models\Assignment;
use App\Models\Document;

class BarChart extends Component
{
    public int    $weekOffset   = 0; // 0 = current week, -1 = last week, etc.
    public string $title        = '7-Day Activity Overview';
    public string $moduleColor  = 'rgba(99, 179, 237, 0.85)';
    public string $assignColor  = 'rgba(154, 230, 180, 0.85)';
    public string $journalColor = 'rgba(246, 173, 85, 0.85)';
    public array  $chartConfig  = [];

    public function mount(
        int    $weekOffset   = 0,
        string $title        = '7-Day Activity Overview',
        string $moduleColor  = 'rgba(99, 179, 237, 0.85)',
        string $assignColor  = 'rgba(154, 230, 180, 0.85)',
        string $journalColor = 'rgba(246, 173, 85, 0.85)',
    ): void {
        $this->weekOffset   = $weekOffset;
        $this->title        = $title;
        $this->moduleColor  = $moduleColor;
        $this->assignColor  = $assignColor;
        $this->journalColor = $journalColor;
        $this->computeConfig();
    }

    public function previousWeek(): void
    {
        $this->weekOffset--;
        $this->computeConfig();
    }

    public function nextWeek(): void
    {
        if ($this->weekOffset < 0) {
            $this->weekOffset++;
            $this->computeConfig();
        }
    }

    public function currentWeek(): void
    {
        $this->weekOffset = 0;
        $this->computeConfig();
    }

    // ─────────────────────────────────────────────
    // Range helpers
    // ─────────────────────────────────────────────

    private function weekStart(): Carbon
    {
        return Carbon::now()->addWeeks($this->weekOffset)->startOfWeek(); // Monday
    }

    private function weekEnd(): Carbon
    {
        return Carbon::now()->addWeeks($this->weekOffset)->endOfWeek();   // Sunday
    }

    private function blankRange(): array
    {
        $range = [];
        $day   = $this->weekStart()->copy();

        while ($day->lte($this->weekEnd())) {
            $range[$day->format('D, M d')] = 0;
            $day->addDay();
        }

        return $range;
    }

    // ─────────────────────────────────────────────
    // Data helpers
    // ─────────────────────────────────────────────

    private function modulesCreated(): array
    {
        $blank = $this->blankRange();

        TrainingModule::query()
            ->notArchived()
            ->whereBetween('created_at', [$this->weekStart()->startOfDay(), $this->weekEnd()->endOfDay()])
            ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->groupBy('day')->orderBy('day')->get()
            ->each(function ($row) use (&$blank) {
                $key = Carbon::parse($row->day)->format('D, M d');
                if (array_key_exists($key, $blank)) $blank[$key] = (int) $row->total;
            });

        return $blank;
    }

    private function usersAssigned(): array
    {
        $blank = $this->blankRange();

        Assignment::query()
            ->whereBetween('created_at', [$this->weekStart()->startOfDay(), $this->weekEnd()->endOfDay()])
            ->selectRaw("DATE(created_at) as day, COUNT(DISTINCT user_id) as total")
            ->groupBy('day')->orderBy('day')->get()
            ->each(function ($row) use (&$blank) {
                $key = Carbon::parse($row->day)->format('D, M d');
                if (array_key_exists($key, $blank)) $blank[$key] = (int) $row->total;
            });

        return $blank;
    }

    private function journalSubmissions(): array
    {
        $blank = $this->blankRange();

        Document::query()
            ->where('isArchived', false)
            ->whereBetween('created_at', [$this->weekStart()->startOfDay(), $this->weekEnd()->endOfDay()])
            ->selectRaw("DATE(created_at) as day, COUNT(DISTINCT user_id) as total")
            ->groupBy('day')->orderBy('day')->get()
            ->each(function ($row) use (&$blank) {
                $key = Carbon::parse($row->day)->format('D, M d');
                if (array_key_exists($key, $blank)) $blank[$key] = (int) $row->total;
            });

        return $blank;
    }

    public function computeConfig(): void
    {
        $modules  = $this->modulesCreated();
        $assigned = $this->usersAssigned();
        $journals = $this->journalSubmissions();

        $this->chartConfig = [
            'labels'   => array_keys($modules),
            'datasets' => [
                [
                    'label'           => 'Modules Created',
                    'data'            => array_values($modules),
                    'backgroundColor' => $this->moduleColor,
                    'borderColor'     => str_replace('0.85', '1', $this->moduleColor),
                    'borderWidth'     => 2,
                    'borderRadius'    => 6,
                    'borderSkipped'   => false,
                ],
                [
                    'label'           => 'Users Assigned',
                    'data'            => array_values($assigned),
                    'backgroundColor' => $this->assignColor,
                    'borderColor'     => str_replace('0.85', '1', $this->assignColor),
                    'borderWidth'     => 2,
                    'borderRadius'    => 6,
                    'borderSkipped'   => false,
                ],
                [
                    'label'           => 'Journal Submissions',
                    'data'            => array_values($journals),
                    'backgroundColor' => $this->journalColor,
                    'borderColor'     => str_replace('0.85', '1', $this->journalColor),
                    'borderWidth'     => 2,
                    'borderRadius'    => 6,
                    'borderSkipped'   => false,
                ],
            ],
        ];

        $this->dispatch('bar-chart-updated', config: $this->chartConfig); // ← added
    }

    public function render()
    {
        return view('livewire.chart.bar-chart', [
            'weekLabel'     => $this->weekStart()->format('M d') . ' – ' . $this->weekEnd()->format('M d, Y'),
            'isCurrentWeek' => $this->weekOffset === 0,
        ]);
    }

}
