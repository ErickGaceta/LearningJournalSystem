@props(['modules' => collect(), 'quarterColor' => 'blue'])


@php
$colorMap = [
    'blue'   => ['primary' => '#3b82f6', 'light' => '#dbeafe'],
    'green'  => ['primary' => '#22c55e', 'light' => '#dcfce7'],
    'yellow' => ['primary' => '#eab308', 'light' => '#fef9c3'],
    'red'    => ['primary' => '#ef4444', 'light' => '#fee2e2'],
    'purple' => ['primary' => '#a855f7', 'light' => '#f3e8ff'],
    'zinc'   => ['primary' => '#71717a', 'light' => '#f4f4f5'],
    'lime'   => ['primary' => '#84cc16', 'light' => '#f7fee7'],
    'sky'    => ['primary' => '#0ea5e9', 'light' => '#e0f2fe'],
    'indigo' => ['primary' => '#6366f1', 'light' => '#e0e7ff'],
    'orange' => ['primary' => '#f97316', 'light' => '#ffedd5'],
    'rose'   => ['primary' => '#f43f5e', 'light' => '#ffe4e6'],
    'teal'   => ['primary' => '#14b8a6', 'light' => '#ccfbf1'],
    'amber'  => ['primary' => '#f59e0b', 'light' => '#fef3c7'],
    'cyan'   => ['primary' => '#06b6d4', 'light' => '#cffafe'],
    'violet' => ['primary' => '#8b5cf6', 'light' => '#ede9fe'],
    'fuchsia'=> ['primary' => '#d946ef', 'light' => '#fae8ff'],
    'pink'   => ['primary' => '#ec4899', 'light' => '#fce7f3'],
];

$qc = $colorMap[$quarterColor] ?? $colorMap['blue'];  // ✅ added — was missing entirely

$statusConfig = [
    'upcoming'  => [
        'label' => 'Upcoming',
        'color' => '#a1a1aa',
        'bg'    => '#f4f4f5',
    ],
    'ongoing'   => [
        'label' => 'Ongoing',
        'color' => '#eab308',
        'bg'    => '#fef9c3',
    ],
    'completed' => [
        'label' => 'Completed',
        'color' => '#22c55e',
        'bg'    => '#dcfce7',
    ],
];

foreach ($modules as $m) {
    $totalAssigned  += $m->assignments->count();
    $totalSubmitted += $m->documents->count();
    match($m->status) {
        'upcoming'  => $countUpcoming++,
        'ongoing'   => $countOngoing++,
        'completed' => $countCompleted++,
        default     => null,
    };
}

$totalModules = $countUpcoming + $countOngoing + $countCompleted;
$submittedPct = $totalAssigned > 0 ? round(($totalSubmitted / $totalAssigned) * 100) : 0;
$completedPct = $totalModules  > 0 ? round(($countCompleted  / $totalModules)  * 100) : 0;

$r             = 45;
$cx = $cy      = 60;
$circumference = 2 * M_PI * $r;

$submittedArc  = $totalAssigned > 0 ? round(($totalSubmitted / $totalAssigned) * $circumference, 2) : 0;
$submittedDash = $submittedArc . ' ' . round($circumference - $submittedArc, 2);

$completedArc  = $totalModules > 0 ? round(($countCompleted / $totalModules) * $circumference, 2) : 0;
$ongoingArc    = $totalModules > 0 ? round(($countOngoing   / $totalModules) * $circumference, 2) : 0;
$upcomingArc   = round($circumference - $completedArc - $ongoingArc, 2);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

    {{-- Card 1: Trainee Submission Rate --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 flex items-center gap-5 shadow-sm">

        <div class="relative flex-shrink-0">
            <svg width="120" height="120" viewBox="0 0 120 120" class="rotate-[-90deg]">
                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                    fill="none" stroke="{{ $qc['light'] }}" stroke-width="12" />
                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                    fill="none" stroke="{{ $qc['primary'] }}" stroke-width="12"
                    stroke-linecap="round"
                    stroke-dasharray="{{ $submittedDash }}"
                    stroke-dashoffset="0"
                    style="transition: stroke-dasharray 0.6s ease;" />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-xl font-bold text-zinc-900 dark:text-zinc-100 leading-none">{{ $submittedPct }}%</span>
                <span class="text-[10px] text-zinc-400 dark:text-zinc-500 mt-0.5 uppercase tracking-wide">rate</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 min-w-0">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 mb-1">Trainee Submission</p>
                <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-snug">Documents submitted vs trainees assigned</p>
            </div>
            <div class="flex flex-col gap-1.5 text-sm">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $qc['primary'] }}"></span>
                    <span class="text-zinc-600 dark:text-zinc-400">Submitted</span>
                    <span class="ml-auto font-semibold text-zinc-900 dark:text-zinc-100 tabular-nums">{{ $totalSubmitted }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $qc['light'] }}; border: 1.5px solid {{ $qc['primary'] }}"></span>
                    <span class="text-zinc-600 dark:text-zinc-400">Pending</span>
                    <span class="ml-auto font-semibold text-zinc-900 dark:text-zinc-100 tabular-nums">{{ max(0, $totalAssigned - $totalSubmitted) }}</span>
                </div>
                <div class="border-t border-zinc-100 dark:border-zinc-700 pt-1 flex items-center gap-2">
                    <span class="inline-block w-3 h-3 rounded-full flex-shrink-0 bg-zinc-300 dark:bg-zinc-600"></span>
                    <span class="text-zinc-500 dark:text-zinc-400">Total Assigned</span>
                    <span class="ml-auto font-semibold text-zinc-900 dark:text-zinc-100 tabular-nums">{{ $totalAssigned }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Module Completion Rate --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 flex items-center gap-5 shadow-sm">

        <div class="relative flex-shrink-0">
            <svg width="120" height="120" viewBox="0 0 120 120" class="rotate-[-90deg]">
                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                    fill="none" stroke="#e4e4e7" stroke-width="12"
                    class="dark:stroke-zinc-700" />
                {{-- Completed segment --}}
                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                    fill="none" stroke="{{ $qc['primary'] }}" stroke-width="12"
                    stroke-dasharray="{{ $completedArc }} {{ $circumference }}"
                    stroke-dashoffset="0" />
                {{-- Ongoing segment (offset by completed arc) --}}
                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                    fill="none" stroke="#eab308" stroke-width="12"
                    stroke-dasharray="{{ $ongoingArc }} {{ $circumference }}"
                    stroke-dashoffset="{{ -$completedArc }}" />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-xl font-bold text-zinc-900 dark:text-zinc-100 leading-none">{{ $completedPct }}%</span>
                <span class="text-[10px] text-zinc-400 dark:text-zinc-500 mt-0.5 uppercase tracking-wide">done</span>
            </div>
        </div>

        <div class="flex flex-col gap-3 min-w-0">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500 mb-1">Module Completion</p>
                <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-snug">Training modules completed this quarter</p>
            </div>
            <div class="flex flex-col gap-1.5 text-sm">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $qc['primary'] }}"></span>
                    <span class="text-zinc-600 dark:text-zinc-400">Completed</span>
                    <span class="ml-auto font-semibold text-zinc-900 dark:text-zinc-100 tabular-nums">{{ $countCompleted }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 rounded-full flex-shrink-0 bg-yellow-400"></span>
                    <span class="text-zinc-600 dark:text-zinc-400">Ongoing</span>
                    <span class="ml-auto font-semibold text-zinc-900 dark:text-zinc-100 tabular-nums">{{ $countOngoing }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 rounded-full flex-shrink-0 bg-zinc-300 dark:bg-zinc-600"></span>
                    <span class="text-zinc-600 dark:text-zinc-400">Upcoming</span>
                    <span class="ml-auto font-semibold text-zinc-900 dark:text-zinc-100 tabular-nums">{{ $countUpcoming }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
