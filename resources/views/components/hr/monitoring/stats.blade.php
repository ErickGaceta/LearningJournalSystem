@props([
    'quarters' => [],
    'colors'   => [],
])

@php
    $totalModules    = 0;
    $totalSubmitted  = 0;
    $totalAssigned   = 0;
    $totalActive     = 0;
    $totalCompleted  = 0;
    $totalUpcoming   = 0;

    foreach ($quarters as $quarter) {
        $totalModules   += $quarter['modules']->count();
        $totalSubmitted += $quarter['modules']->sum(fn($m) => $m->documentsByUser->count());
        $totalAssigned  += $quarter['modules']->sum(fn($m) => $m->assignments->count());
        $totalActive    += $quarter['modules']->where('status', 'ongoing')->count();
        $totalCompleted += $quarter['modules']->where('status', 'completed')->count();
        $totalUpcoming  += $quarter['modules']->where('status', 'upcoming')->count();
    }

    $stats = [
        [
            'label'  => 'Total Trainings',
            'value'  => $totalModules,
            'color'  => 'zinc',
            'icon'   => 'academic-cap',
        ],
        [
            'label'  => 'Submitted',
            'value'  => $totalSubmitted . ' / ' . $totalAssigned,
            'color'  => 'blue',
            'icon'   => 'document-check',
        ],
        [
            'label'  => 'Active',
            'value'  => $totalActive,
            'color'  => 'orange',
            'icon'   => 'play-circle',
        ],
        [
            'label'  => 'Completed',
            'value'  => $totalCompleted,
            'color'  => 'green',
            'icon'   => 'check-circle',
        ],
        [
            'label'  => 'Upcoming',
            'value'  => $totalUpcoming,
            'color'  => 'violet',
            'icon'   => 'clock',
        ],
    ];
@endphp

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
    @foreach ($stats as $stat)
        <div class="flex flex-col gap-1.5 rounded-xl border border-white/10 bg-white/5 px-4 py-3">
            <flux:text size="xs" class="text-zinc-400 leading-none">{{ $stat['label'] }}</flux:text>
            <div class="flex items-center justify-between gap-2">
                <span class="text-lg font-semibold text-white leading-none">{{ $stat['value'] }}</span>
                <flux:badge color="{{ $stat['color'] }}" size="sm" icon="{{ $stat['icon'] }}" />
            </div>
        </div>
    @endforeach
</div>