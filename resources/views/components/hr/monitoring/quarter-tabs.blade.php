@props(['quarters', 'colors', 'year'])

<div
    x-data="{ activeQ: 1 }"
    class="rounded-xl border border-white/10 overflow-hidden"
>

    {{-- Quarter Filter Tabs --}}
    <div class="flex border-b border-white/10 bg-white/5">
        @foreach ($quarters as $num => $quarter)
            @php
                $moduleCount = $quarter['modules']->count();
                $color       = $colors[$num] ?? 'zinc';
            @endphp

            <button
                type="button"
                @click="activeQ = {{ $num }}"
                :class="activeQ === {{ $num }}
                    ? 'border-b-2 border-{{ $color }}-400 bg-white/10 text-white'
                    : 'text-zinc-400 hover:text-white hover:bg-white/5'"
                class="flex-1 flex flex-col items-center gap-1 px-4 py-3 text-sm font-medium transition-colors"
            >
                <flux:badge color="{{ $color }}" size="sm">Q{{ $num }}</flux:badge>
                <span class="text-xs leading-none">{{ $quarter['label'] }}</span>
                <flux:badge
                    color="{{ $moduleCount > 0 ? $color : 'zinc' }}"
                    size="sm"
                >
                    {{ $moduleCount }} {{ Str::plural('training', $moduleCount) }}
                </flux:badge>
            </button>
        @endforeach
    </div>

    {{-- Quarter Panels --}}
    @foreach ($quarters as $num => $quarter)
        @php
            $moduleCount = $quarter['modules']->count();
            $color       = $colors[$num] ?? 'zinc';
        @endphp

        <div x-show="activeQ === {{ $num }}" x-transition>

            {{-- Panel Header --}}
            <div class="flex items-center justify-between px-5 py-4 bg-white/5">
                <div>
                    <flux:heading size="sm" class="leading-none">{{ $quarter['label'] }}</flux:heading>
                    <flux:text size="xs" class="text-zinc-400 mt-0.5">{{ $quarter['range'] }}</flux:text>
                </div>
                <flux:badge color="{{ $moduleCount > 0 ? $color : 'zinc' }}" size="sm">
                    {{ $moduleCount }} {{ Str::plural('training', $moduleCount) }}
                </flux:badge>
            </div>

            {{-- Panel Body --}}
            @if ($moduleCount === 0)
                <div class="px-5 py-8 text-center">
                    <flux:text class="text-zinc-500">
                        No trainings in {{ $quarter['label'] }} {{ $year }}.
                    </flux:text>
                </div>
            @else
                <x-hr.monitoring.modules-table
                    :modules="$quarter['modules']"
                    :quarter-color="$color"
                />
            @endif

        </div>
    @endforeach

</div>