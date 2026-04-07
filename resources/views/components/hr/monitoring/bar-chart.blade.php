@props(['modules', 'paginator'])

@php
$statusConfig = [
    'upcoming'  => ['color' => '#a1a1aa', 'label' => 'Upcoming'],
    'pending'   => ['color' => '#eab308', 'label' => 'Pending'],
    'ongoing'   => ['color' => '#3b82f6', 'label' => 'Ongoing'],
    'completed' => ['color' => '#22c55e', 'label' => 'Completed'],
    'cancelled' => ['color' => '#ef4444', 'label' => 'Cancelled'],
];

$statusCounts = ['upcoming' => 0, 'pending' => 0, 'ongoing' => 0, 'completed' => 0, 'cancelled' => 0];
foreach ($modules as $m) {
    if (isset($statusCounts[$m->status])) $statusCounts[$m->status]++;
}

$completedModules = $modules->filter(fn($m) => $m->status === 'completed')->values();

$submissionData = $completedModules->map(function ($m) {
    $assigned = $m->assignments->count();
    $docs     = $m->documents->count();
    $pct      = $assigned > 0 ? round(($docs / $assigned) * 100) : 0;
    return [
        'title' => $m->title,
        'pct'   => $pct,
        'color' => match(true) {
            $pct >= 75 => '#22c55e',
            $pct >= 50 => '#3b82f6',
            $pct >= 25 => '#eab308',
            default    => '#ef4444',
        },
    ];
})->values();

$submissionChartHeight = max(160, $completedModules->count() * 44 + 40);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-4">

    {{-- Status Breakdown --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
        <p class="text-[11px] font-medium tracking-widest text-zinc-400 uppercase mb-3">
            Status breakdown
        </p>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg px-3 py-2">
                <p class="text-xl font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $paginator->total() }}
                </p>
                <p class="text-xs text-zinc-500 mt-0.5">Total trainings</p>
            </div>
            <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg px-3 py-2">
                <p class="text-xl font-medium text-zinc-900 dark:text-zinc-100">
                    {{ $statusCounts['completed'] }}
                </p>
                <p class="text-xs text-zinc-500 mt-0.5">Completed</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-3">
            @foreach($statusConfig as $key => $cfg)
            <span class="flex items-center gap-1 text-[11px] text-zinc-500">
                <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background: {{ $cfg['color'] }}"></span>
                {{ $cfg['label'] }} {{ $statusCounts[$key] }}
            </span>
            @endforeach
        </div>

        <div style="position: relative; width: 100%; height: 180px;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    {{-- Submission Rate --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
        <p class="text-[11px] font-medium tracking-widest text-zinc-400 uppercase mb-3">
            Submission rate — completed trainings
        </p>

        @if($completedModules->isEmpty())
            <div class="flex items-center justify-center h-40">
                <p class="text-sm text-zinc-400">No completed trainings on this page.</p>
            </div>
        @else
            <div class="flex flex-wrap gap-3 mb-3">
                @foreach([['#22c55e','≥ 75%'],['#3b82f6','50–74%'],['#eab308','25–49%'],['#ef4444','< 25%']] as [$color, $label])
                <span class="flex items-center gap-1 text-[11px] text-zinc-500">
                    <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background: {{ $color }}"></span>
                    {{ $label }}
                </span>
                @endforeach
            </div>

            <div style="position: relative; width: 100%; height: {{ $submissionChartHeight }}px;">
                <canvas id="submissionChart"></canvas>
            </div>
        @endif
    </div>

</div>

@once
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
@endonce

<script>
(function () {
    function initCharts() {
        const isDark    = matchMedia('(prefers-color-scheme: dark)').matches;
        const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
        const tickColor = isDark ? '#a1a1aa' : '#71717a';
        const lblColor  = isDark ? '#d4d4d8' : '#3f3f46';

        const statusEl = document.getElementById('statusChart');
        if (statusEl) {
            if (Chart.getChart(statusEl)) Chart.getChart(statusEl).destroy();
            new Chart(statusEl, {
                type: 'bar',
                data: {
                    labels: ['Upcoming', 'Pending', 'Ongoing', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $statusCounts['upcoming'] }},
                            {{ $statusCounts['pending'] }},
                            {{ $statusCounts['ongoing'] }},
                            {{ $statusCounts['completed'] }},
                            {{ $statusCounts['cancelled'] }},
                        ],
                        backgroundColor: ['#a1a1aa', '#eab308', '#3b82f6', '#22c55e', '#ef4444'],
                        borderRadius: 5,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: ctx => ' ' + ctx.parsed.y + ' trainings' }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: tickColor, font: { size: 11 }, autoSkip: false },
                            grid: { display: false },
                            border: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: tickColor,
                                font: { size: 11 },
                                stepSize: 1,
                                callback: v => Math.round(v)
                            },
                            grid: { color: gridColor },
                            border: { display: false }
                        }
                    }
                }
            });
        }

        @if($submissionData->isNotEmpty())
        const subEl = document.getElementById('submissionChart');
        if (subEl) {
            if (Chart.getChart(subEl)) Chart.getChart(subEl).destroy();
            const subData = @json($submissionData);
            new Chart(subEl, {
                type: 'bar',
                data: {
                    labels: subData.map(d => d.title),
                    datasets: [{
                        data: subData.map(d => d.pct),
                        backgroundColor: subData.map(d => d.color),
                        borderRadius: 5,
                        borderSkipped: false,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: ctx => ' ' + Math.round(ctx.parsed.x) + '%' }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { color: tickColor, font: { size: 11 }, callback: v => v + '%' },
                            grid: { color: gridColor },
                            border: { display: false }
                        },
                        y: {
                            ticks: { color: lblColor, font: { size: 11 } },
                            grid: { display: false },
                            border: { display: false }
                        }
                    }
                }
            });
        }
        @endif
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }

    document.addEventListener('livewire:navigated', initCharts);
})();
</script>