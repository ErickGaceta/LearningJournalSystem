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
    {{-- Quarter tabs --}}
    @php
    $quarterColors = [1 => 'teal', 2 => 'lime', 3 => 'yellow', 4 => 'violet'];
    @endphp

    <div wire:loading.class="opacity-50 pointer-events-none">

        <x-hr.monitoring.quarter-tabs
            :quarters="$quarters"
            :colors="$quarterColors"
            :year="$year" />
    </div>

</div>

</div>