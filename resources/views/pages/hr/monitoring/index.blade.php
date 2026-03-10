<x-layouts::app :title="__('Monitoring')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- ── Search + Year filter ── --}}
        <form method="GET" action="{{ route('hr.monitoring.index') }}" class="p-4 w-full justify-end flex">
            <div class="flex gap-3 items-center w-25 justify-end">
                <flux:select name="year" class="w-32">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                    @endforeach
                </flux:select>

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
                    :color="$quarterColors[$num]".

                    :year="$year" />
            @endforeach
        </div>

    </div>

    <x-pdf-preview-modal :url="url('hr/monitoring/documents')" event="open-document-preview" />

</x-layouts::app>
