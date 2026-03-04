@props(['num', 'quarter', 'color', 'year'])

@php $moduleCount = $quarter['modules']->count(); @endphp

<div x-data="{ open: {{ $moduleCount > 0 ? 'true' : 'false' }} }"
     class="rounded-xl border border-white/10 overflow-hidden">

    {{-- Header --}}
    <button type="button" @click="open = !open"
        class="w-full flex items-center justify-between px-5 py-4 bg-white/5 hover:bg-white/10 transition-colors text-left">
        <div class="flex items-center gap-3">
            <flux:badge color="{{ $color }}" size="sm">Q{{ $num }}</flux:badge>
            <div>
                <flux:heading size="sm" class="leading-none">{{ $quarter['label'] }}</flux:heading>
                <flux:text size="xs" class="text-zinc-400 mt-0.5">{{ $quarter['range'] }}</flux:text>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <flux:badge color="{{ $moduleCount > 0 ? $color : 'zinc' }}" size="sm">
                {{ $moduleCount }} {{ Str::plural('training', $moduleCount) }}
            </flux:badge>
            <svg class="w-4 h-4 text-zinc-400 transition-transform duration-200"
                 :class="open ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </button>

    {{-- Body --}}
    <div x-show="open" x-collapse>
        @if($moduleCount === 0)
            <div class="px-5 py-8 text-center">
                <flux:text class="text-zinc-500">No trainings in {{ $quarter['label'] }} {{ $year }}.</flux:text>
            </div>
        @else
            <x-hr.monitoring.modules-table
                :modules="$quarter['modules']"
                :quarter-color="$color" />
        @endif
    </div>

</div>
