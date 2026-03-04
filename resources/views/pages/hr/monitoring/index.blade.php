<x-layouts::app :title="__('Monitoring')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- ── Search + Year filter ── --}}
        <form method="GET" action="{{ route('hr.monitoring.index') }}" class="p-4">
            <div class="flex gap-3 justify-center items-center">
                <flux:select name="year" class="w-32">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                    @endforeach
                </flux:select>

                <div class="flex-1 relative">
                    <flux:input
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by training title, venue, conductor, or employee…"
                        icon:trailing="magnifying-glass"
                        class="w-full rounded-3xl" />
                </div>

                <flux:button type="submit" variant="primary" icon="magnifying-glass" color="lime" square />

                @if(request('search') || request('year'))
                    <flux:button :href="route('hr.monitoring.index')" variant="ghost">Clear</flux:button>
                @endif
            </div>
        </form>

        {{-- ── Quarter panels ── --}}
        @php
            $quarterColors = [1 => 'teal', 2 => 'lime', 3 => 'sky', 4 => 'violet'];
        @endphp

        <div class="flex flex-col gap-4">
            @foreach($quarters as $num => $quarter)
                <x-hr.monitoring.quarter-panel
                    :num="$num"
                    :quarter="$quarter"
                    :color="$quarterColors[$num]"
                    :year="$year" />
            @endforeach
        </div>

    </div>
</x-layouts::app>