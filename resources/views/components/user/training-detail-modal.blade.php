@props(['assignment'])

@php
    $now = now();
    $start = $assignment->module->datestart;
    $end = $assignment->module->dateend;
@endphp

<flux:modal name="training-detail-{{ $assignment->id }}" style="width: 40vw;">
    <div class="p-2 bg-white dark:bg-neutral-800 space-y-4">
        <div>
            <flux:heading size="lg">{{ $assignment->module->title }}</flux:heading>
            <flux:text variant="subtle" class="text-sm mt-1">Training Details</flux:text>
        </div>

        <flux:separator />

        <div class="grid grid-cols-1 lg:grid-cols-2 items-start justify-center gap-3">
            <div class="flex flex-col gap-3">
                <flux:heading size="sm">Basic Information</flux:heading>

                <div class="flex items-center gap-2">
                    <flux:icon.clock size="sm" class="text-neutral-400" />
                    <flux:text class="text-neutral-500">Duration</flux:text>
                    <flux:text>{{ $assignment->module->hours }} hours</flux:text>
                </div>

                <div class="flex items-center gap-2">
                    <flux:icon.map-pin size="sm" class="text-neutral-400" />
                    <flux:text class="text-neutral-500">Venue</flux:text>
                    <flux:text>{{ $assignment->module->venue }}</flux:text>
                </div>

                <div class="flex items-center gap-2">
                    <flux:icon.user size="sm" class="text-neutral-400" />
                    <flux:text class="text-neutral-500">Conducted by</flux:text>
                    <flux:text>{{ $assignment->module->conductedby }}</flux:text>
                </div>

                @if($assignment->module->registration_fee)
                <div class="flex items-center gap-2">
                    <flux:icon.banknotes size="sm" class="text-neutral-400" />
                    <flux:text class="text-neutral-500">Fee</flux:text>
                    <flux:text>{{ $assignment->module->registration_fee }}</flux:text>
                </div>
                @endif
            </div>

            <div class="flex flex-col gap-3">
                <flux:heading size="sm">Schedule</flux:heading>

                <div class="flex items-center gap-2">
                    <flux:icon.calendar size="sm" class="text-neutral-400" />
                    <flux:text class="text-neutral-500">Start</flux:text>
                    <flux:text>{{ $start->format('F d, Y') }}</flux:text>
                </div>

                <div class="flex items-center gap-2">
                    <flux:icon.calendar size="sm" class="text-neutral-400" />
                    <flux:text class="text-neutral-500">End</flux:text>
                    <flux:text>{{ $end->format('F d, Y') }}</flux:text>
                </div>

                <div class="flex items-center gap-2">
                    <flux:icon.signal size="sm" class="text-neutral-400" />
                    <flux:text class="text-neutral-500">Status</flux:text>
                    @if ($now->lt($start))
                        <flux:badge color="amber" size="sm">Pending</flux:badge>
                    @elseif ($now->between($start, $end))
                        <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                    @else
                        <flux:badge color="zinc" size="sm">Completed</flux:badge>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex justify-end border-t border-neutral-200 dark:border-neutral-700">
        <flux:modal.close>
            <flux:button variant="ghost" size="sm">Close</flux:button>
        </flux:modal.close>
    </div>
</flux:modal>