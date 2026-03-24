@props(['modules', 'quarterColor', 'paginator', 'chartModules' => null])

@php
$statusConfig = [
    'upcoming' => ['color' => 'zinc',   'label' => 'Upcoming'],
    'ongoing'  => ['color' => 'yellow', 'label' => 'Ongoing'],
    'completed'=> ['color' => 'green',  'label' => 'Completed'],
];

// Use a dedicated chartModules prop (full unpaginated set) if provided,
// otherwise fall back to the current page — caller should pass the full set.
$chartData = $chartModules ?? $modules;

// Build chart datasets only from completed modules that have submissions.
$chartLabels  = [];
$chartValues  = [];
$chartAssigned= [];

foreach ($chartData as $m) {
    $docs  = $m->documents->count();
    $asgnd = $m->assignments->count();
    if ($m->status === 'completed' && $asgnd > 0) {
        // Truncate long titles for the legend.
        $chartLabels[]   = strlen($m->title) > 38 ? substr($m->title, 0, 35).'...' : $m->title;
        $chartValues[]   = $docs;
        $chartAssigned[] = $asgnd;
    }
}

$totalSubmitted = array_sum($chartValues);
$totalAssigned  = array_sum($chartAssigned);

$chartId = 'pie-' . uniqid();

// Quarter/year context from request params (adjust key names to match yours).
$activeYear    = request('year',    date('Y'));
$activeQuarter = request('quarter', 'Q' . ceil(date('n') / 3));
@endphp

{{-- ───────────────────────────────────────────────────
     PIE CHART CARD
────────────────────────────────────────────────────── --}}
<div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm">

    {{-- Card header --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-100 dark:border-zinc-800">
        <div>
            <h3 class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                Trainee Attendance by Training
            </h3>
            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">
                Completed modules · {{ $activeYear }} · {{ $activeQuarter }}
                @if($chartModules === null && $paginator->hasPages())
                    <span class="ml-1 italic">(current page only — pass <code>:chart-modules</code> for full view)</span>
                @endif
            </p>
        </div>

        {{-- Summary badges --}}
        <div class="flex items-center gap-3 text-right">
            <div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 leading-none">Submitted</p>
                <p class="text-lg font-bold text-zinc-800 dark:text-zinc-100 leading-tight">
                    {{ $totalSubmitted }}
                </p>
            </div>
            <div class="w-px h-8 bg-zinc-200 dark:bg-zinc-700"></div>
            <div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 leading-none">Assigned</p>
                <p class="text-lg font-bold text-zinc-800 dark:text-zinc-100 leading-tight">
                    {{ $totalAssigned }}
                </p>
            </div>
            <div class="w-px h-8 bg-zinc-200 dark:bg-zinc-700"></div>
            <div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 leading-none">Rate</p>
                <p class="text-lg font-bold
                    {{ $totalAssigned > 0 && round(($totalSubmitted / $totalAssigned) * 100) >= 80 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}
                    leading-tight">
                    {{ $totalAssigned > 0 ? round(($totalSubmitted / $totalAssigned) * 100) : 0 }}%
                </p>
            </div>
        </div>
    </div>

    {{-- Chart body --}}
    @if(count($chartValues) > 0)
    <div class="flex flex-col md:flex-row items-center gap-6 p-5">

        {{-- Canvas --}}
        <div class="flex-shrink-0 w-52 h-52 relative">
            <canvas id="{{ $chartId }}" width="208" height="208"></canvas>
            {{-- Centre label --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <span class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">
                    {{ $totalAssigned > 0 ? round(($totalSubmitted / $totalAssigned) * 100) : 0 }}%
                </span>
                <span class="text-[10px] text-zinc-400 dark:text-zinc-500 uppercase tracking-wide">overall</span>
            </div>
        </div>

        {{-- Legend / per-module breakdown --}}
        <div class="flex-1 w-full overflow-y-auto max-h-52 pr-1 space-y-2" id="{{ $chartId }}-legend">
            {{-- Populated by JS --}}
        </div>

    </div>
    @else
    <div class="flex flex-col items-center justify-center py-12 text-zinc-400 dark:text-zinc-600">
        <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
        </svg>
        <p class="text-sm">No completed modules to chart yet.</p>
    </div>
    @endif
</div>

{{-- ───────────────────────────────────────────────────
     TABLE (original, unchanged below)
────────────────────────────────────────────────────── --}}
<div class="overflow-x-auto">
    <flux:table x-data="{ expanded: { {{ (int) request('expanded_module', 0) }}: {{ request('expanded_module') ? 'true' : 'false' }} } }">
        <flux:table.columns>
            <flux:table.column align="end">#</flux:table.column>
            <flux:table.column>Training Title</flux:table.column>
            <flux:table.column>Venue</flux:table.column>
            <flux:table.column>Date Start - Date End</flux:table.column>
            <flux:table.column>Hours</flux:table.column>
            <flux:table.column>Conducted By</flux:table.column>
            <flux:table.column align="center">Assigned</flux:table.column>
            <flux:table.column align="center">Status</flux:table.column>
            <flux:table.column align="center">Notify</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach($modules as $mi => $module)
            @php
            $assignmentCount = $module->assignments->count();
            $documentCount   = $module->documents->count();
            $isCompleted     = $module->status === 'completed';
            $sc              = $statusConfig[$module->status];

            $pct = $assignmentCount > 0
                ? round(($documentCount / $assignmentCount) * 100)
                : 0;
            $barWidth = match(true) {
                $pct >= 100 => 'w-full',
                $pct >= 92  => 'w-11/12',
                $pct >= 83  => 'w-5/6',
                $pct >= 75  => 'w-3/4',
                $pct >= 67  => 'w-2/3',
                $pct >= 58  => 'w-7/12',
                $pct >= 50  => 'w-1/2',
                $pct >= 42  => 'w-5/12',
                $pct >= 33  => 'w-1/3',
                $pct >= 25  => 'w-1/4',
                $pct >= 17  => 'w-1/6',
                $pct >= 8   => 'w-1/12',
                default     => 'w-0',
            };
            @endphp

            {{-- Module row --}}
            <flux:table.row
                :key="'mod-'.$module->id"
                class="{{ $isCompleted ? 'cursor-pointer' : '' }}"
                x-on:click="{{ $isCompleted ? 'expanded['.$module->id.'] ? expanded = {} : expanded = { '.$module->id.': true }' : '' }}">

                <flux:table.cell align="end">
                    <span class="text-sm text-zinc-500">
                        {{ $paginator->firstItem() + $mi }}
                    </span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm font-medium">{{ $module->title }}</span>
                    @if($isCompleted)
                    <flux:text size="xs" class="text-zinc-500 mt-0.5">
                        {{ $documentCount }} / {{ $assignmentCount }} submitted
                    </flux:text>
                    @endif
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm truncate max-w-xs block">{{ $module->venue }}</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm whitespace-nowrap">
                        {{ $module->datestart->format('M d, Y') }} – {{ $module->dateend->format('M d, Y') }}
                    </span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm">{{ $module->hours }} hrs</span>
                </flux:table.cell>

                <flux:table.cell>
                    <span class="text-sm">{{ $module->conductedby }}</span>
                </flux:table.cell>

                <flux:table.cell align="center">
                    <flux:badge color="{{ $quarterColor }}" size="sm">
                        {{ $assignmentCount }}
                    </flux:badge>
                </flux:table.cell>

                <flux:table.cell align="center">
                    <flux:badge color="{{ $sc['color'] }}" size="sm">
                        {{ $sc['label'] }}
                    </flux:badge>
                </flux:table.cell>

                <flux:table.cell align="center">
                    @if($isCompleted)
                    <form action="{{ route('hr.modules.notify', $module) }}" method="POST"
                        x-data="{ sent: false }"
                        @submit.prevent="sent = true; $el.submit();">
                        @csrf
                        <flux:button
                            type="submit"
                            size="sm"
                            icon="envelope"
                            variant="ghost"
                            x-bind:disabled="sent"
                            x-tooltip="'Notify unsubmitted users'">
                        </flux:button>
                    </form>
                    @endif
                </flux:table.cell>

                <flux:table.cell align="start">
                    @if($isCompleted)
                    <svg class="w-3 h-3 text-zinc-900 dark:text-zinc-300 mx-auto border border-zinc-300 dark:border-zinc-600 rounded-full transition-transform duration-300"
                        style="transition: transform 300ms cubic-bezier(0.4, 0, 0.2, 1)"
                        :style="expanded[{{ $module->id }}] ? 'transform: rotate(90deg)' : 'transform: rotate(0deg)'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    @endif
                </flux:table.cell>

            </flux:table.row>

            {{-- Inner submissions row --}}
            @if($isCompleted)
            <flux:table.row :key="'docs-'.$module->id">
                <td colspan="10">
                    <div x-show="expanded[{{ $module->id }}]" x-collapse.duration.300ms>
                        <x-hr.monitoring.submissions-table
                            :module="$module"
                            :assignment-count="$assignmentCount"
                            :document-count="$documentCount"
                            :bar-width="$barWidth"
                            :pct="$pct" />
                    </div>
                </td>
            </flux:table.row>
            @endif

            @endforeach
        </flux:table.rows>
    </flux:table>

    {{-- Pagination --}}
    <x-pagination :paginator="$paginator" />
</div>

{{-- ───────────────────────────────────────────────────
     CHART SCRIPT  (Chart.js doughnut)
────────────────────────────────────────────────────── --}}
@if(count($chartValues) > 0)
@once
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endonce

<script>
(function () {
    const LABELS   = @json($chartLabels);
    const DOCS     = @json($chartValues);    // submitted
    const ASSIGNED = @json($chartAssigned); // total assigned per module
    const TOTAL    = ASSIGNED.reduce((a, b) => a + b, 0);
    const CHART_ID = @json($chartId);

    // Palette — cycles if more modules than colours.
    const PALETTE = [
        '#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6',
        '#06b6d4','#f97316','#84cc16','#ec4899','#14b8a6',
        '#a855f7','#eab308','#6366f1','#22c55e','#fb923c',
    ];

    function colour(i) { return PALETTE[i % PALETTE.length]; }

    // Dark-mode aware text colour.
    function fgColour() {
        return document.documentElement.classList.contains('dark')
            ? '#e4e4e7' : '#18181b';
    }

    // ── Build doughnut dataset ──────────────────────────────
    // Each slice = submitted docs for that module.
    // A "not submitted" slice fills the remainder per module —
    // but for a simpler overall chart we show one slice per
    // module (submitted) plus one aggregate "not submitted" slice.
    const notSubmitted = ASSIGNED.reduce((sum, a, i) => sum + (a - DOCS[i]), 0);

    const dataValues  = [...DOCS, notSubmitted];
    const dataLabels  = [...LABELS, 'Not Submitted'];
    const dataColours = LABELS.map((_, i) => colour(i)).concat(['#d1d5db']);
    const dataBorders = dataColours.map(c => c);

    // ── Chart ───────────────────────────────────────────────
    const canvas = document.getElementById(CHART_ID);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: dataLabels,
            datasets: [{
                data: dataValues,
                backgroundColor: dataColours,
                borderColor: dataColours,
                borderWidth: 2,
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '68%',
            responsive: false,
            animation: { animateRotate: true, duration: 700 },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            const idx   = ctx.dataIndex;
                            const label = ctx.label;
                            const val   = ctx.raw;
                            const base  = idx < ASSIGNED.length ? ASSIGNED[idx] : TOTAL;
                            const pct   = base > 0 ? Math.round((val / base) * 100) : 0;
                            return ` ${label}: ${val} / ${base} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });

    // ── Custom legend ────────────────────────────────────────
    const legendEl = document.getElementById(CHART_ID + '-legend');
    if (!legendEl) return;

    LABELS.forEach((label, i) => {
        const submitted = DOCS[i];
        const assigned  = ASSIGNED[i];
        const pct       = assigned > 0 ? Math.round((submitted / assigned) * 100) : 0;
        const col       = colour(i);

        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 group';
        row.innerHTML = `
            <span class="flex-shrink-0 w-2.5 h-2.5 rounded-full" style="background:${col}"></span>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-center gap-2">
                    <span class="text-xs text-zinc-700 dark:text-zinc-300 truncate">${label}</span>
                    <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 flex-shrink-0">
                        ${submitted}/${assigned}
                    </span>
                </div>
                <div class="mt-0.5 h-1 w-full rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                    <div class="h-1 rounded-full transition-all duration-500"
                         style="width:${pct}%; background:${col}"></div>
                </div>
            </div>
            <span class="text-[10px] font-semibold flex-shrink-0 w-8 text-right"
                  style="color:${col}">${pct}%</span>
        `;
        legendEl.appendChild(row);
    });

    // "Not submitted" row
    if (notSubmitted > 0) {
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 pt-1 mt-1 border-t border-zinc-100 dark:border-zinc-800';
        row.innerHTML = `
            <span class="flex-shrink-0 w-2.5 h-2.5 rounded-full bg-zinc-300 dark:bg-zinc-600"></span>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Not submitted</span>
                    <span class="text-xs text-zinc-400">${notSubmitted}/${TOTAL}</span>
                </div>
            </div>
            <span class="text-[10px] font-semibold w-8 text-right text-zinc-400">
                ${TOTAL > 0 ? Math.round((notSubmitted / TOTAL) * 100) : 0}%
            </span>
        `;
        legendEl.appendChild(row);
    }
})();
</script>
@endif
