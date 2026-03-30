@props(['quarters', 'colors', 'year'])

<div x-data="{ activeQ: 1 }">

    {{-- Gauges --}}
    @foreach ($quarters as $num => $quarter)
    @php
    $color = $colors[$num] ?? 'zinc';
    $submitted = $quarter['modules']->sum(fn($m) => $m->documentsByUser->count());
    $totalAssigned = $quarter['modules']->sum(fn($m) => $m->assignments->count());

    $activeCount = $quarter['modules']->where('status', 'ongoing')->count();
    $completedCount = $quarter['modules']->where('status', 'completed')->count();
    $upcomingCount = $quarter['modules']->where('status', 'upcoming')->count();
    @endphp
    <div x-show="activeQ === {{ $num }}" x-cloak class="flex gap-2">
        <x-hr.monitoring.pie-chart
            :chart-value="$submitted"
            :max-value="max($totalAssigned, 1)"
            :stroke-color="$color"
            :text-label="$submitted . '/' . $totalAssigned . ' submitted'" />
        <x-hr.monitoring.comparison-chart
            :chart-value1="$activeCount"
            :chart-value2="$completedCount"
            :chart-value3="$upcomingCount"
            stroke-color1="orange"
            stroke-color2="green"
            stroke-color3="violet"
            label1="Active"
            label2="Completed"
            label3="Upcoming"
            text-label="Module Status" />
    </div>
    @endforeach

    <div class="rounded-xl border border-white/10 overflow-hidden">
        <div class="flex border-b border-white/10 bg-white/5">
            @foreach ($quarters as $num => $quarter)
            @php
            $moduleCount = $quarter['modules']->count();
            $color = $colors[$num] ?? 'zinc';
            @endphp
            <button
                type="button"
                @click="activeQ = {{ $num }}"
                :class="activeQ === {{ $num }}
                        ? 'border-b-2 border-{{ $color }}-400 bg-white/10 text-white'
                        : 'text-zinc-400 hover:text-white hover:bg-white/5'"
                class="flex-1 flex flex-col items-center gap-1 px-4 py-3 text-sm font-medium transition-colors">
                <flux:badge color="{{ $color }}" size="sm">Q{{ $num }}</flux:badge>
                <span class="text-xs leading-none">{{ $quarter['label'] }}</span>
                <flux:badge color="{{ $moduleCount > 0 ? $color : 'zinc' }}" size="sm">
                    {{ $moduleCount }} {{ Str::plural('training', $moduleCount) }}
                </flux:badge>
            </button>
            @endforeach
        </div>

        {{-- Quarter Panels --}}
        @foreach ($quarters as $num => $quarter)
        @php
        $moduleCount = $quarter['modules']->count();
        $color = $colors[$num] ?? 'zinc';
        @endphp
        <div x-show="activeQ === {{ $num }}"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1">

            <div class="flex items-center justify-between px-5 py-4 bg-white/5">
                <div>
                    <flux:heading size="sm" class="leading-none">{{ $quarter['label'] }}</flux:heading>
                    <flux:text size="xs" class="text-zinc-400 mt-0.5">{{ $quarter['range'] }}</flux:text>
                </div>
                <flux:badge color="{{ $moduleCount > 0 ? $color : 'zinc' }}" size="sm">
                    {{ $moduleCount }} {{ Str::plural('training', $moduleCount) }}
                </flux:badge>
            </div>

            @if ($moduleCount === 0)
            <div class="px-5 py-8 text-center">
                <flux:text class="text-zinc-500">No trainings in {{ $quarter['label'] }} {{ $year }}.</flux:text>
            </div>
            @else
            <x-hr.monitoring.modules-table
                :modules="$quarter['paginator']->items()"
                :paginator="$quarter['paginator']"
                :quarter-color="$color" />
            @endif
        </div>
        @endforeach
    </div>

</div>