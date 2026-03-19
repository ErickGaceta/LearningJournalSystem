<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    {{-- Year filter --}}
    <div class="p-4 w-full justify-end flex">
        <div class="flex gap-3 items-center justify-end">
            <flux:select wire:model.live="year" class="w-32">
                @foreach($availableYears as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    {{-- Quarter panels --}}
    @php
        $quarterColors = [1 => 'teal', 2 => 'lime', 3 => 'sky', 4 => 'violet'];
    @endphp

    <div class="flex flex-col gap-4" wire:loading.class="opacity-50 pointer-events-none">
        @foreach($quarters as $num => $quarter)
            <x-hr.monitoring.quarter-panel
                :num="$num"
                :quarter="$quarter"
                :color="$quarterColors[$num]"
                :year="$year" />
        @endforeach
    </div>

</div>