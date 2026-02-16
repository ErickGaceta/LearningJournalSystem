<x-layouts::app :title="__('Training Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <flux:heading size="xl">Training Details</flux:heading>
                <flux:subheading>View information about this training</flux:subheading>
            </div>
            <flux:button href="{{ route('user.trainings.index') }}" variant="ghost" icon="arrow-left">
                Back
            </flux:button>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <flux:card class="flex flex-col gap-4">
                <flux:heading size="lg">{{ $assignment->module->title }}</flux:heading>

                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <flux:icon.clock size="sm" class="text-neutral-400" />
                        <flux:text size="sm" class="text-neutral-500">Duration</flux:text>
                        <flux:text size="sm">{{ $assignment->module->hours }} hours</flux:text>
                    </div>

                    <div class="flex items-center gap-2">
                        <flux:icon.map-pin size="sm" class="text-neutral-400" />
                        <flux:text size="sm" class="text-neutral-500">Venue</flux:text>
                        <flux:text size="sm">{{ $assignment->module->venue }}</flux:text>
                    </div>

                    <div class="flex items-center gap-2">
                        <flux:icon.user size="sm" class="text-neutral-400" />
                        <flux:text size="sm" class="text-neutral-500">Conducted by</flux:text>
                        <flux:text size="sm">{{ $assignment->module->conductedby }}</flux:text>
                    </div>

                    @if($assignment->module->registration_fee)
                    <div class="flex items-center gap-2">
                        <flux:icon.banknotes size="sm" class="text-neutral-400" />
                        <flux:text size="sm" class="text-neutral-500">Registration Fee</flux:text>
                        <flux:text size="sm">{{ $assignment->module->registration_fee }}</flux:text>
                    </div>
                    @endif
                </div>
            </flux:card>

            <flux:card class="flex flex-col gap-4">
                <flux:heading size="lg">Schedule</flux:heading>

                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <flux:icon.calendar size="sm" class="text-neutral-400" />
                        <flux:text size="sm" class="text-neutral-500">Start Date</flux:text>
                        <flux:text size="sm">{{ \Carbon\Carbon::parse($assignment->module->datestart)->format('F d, Y') }}</flux:text>
                    </div>

                    <div class="flex items-center gap-2">
                        <flux:icon.calendar size="sm" class="text-neutral-400" />
                        <flux:text size="sm" class="text-neutral-500">End Date</flux:text>
                        <flux:text size="sm">{{ \Carbon\Carbon::parse($assignment->module->dateend)->format('F d, Y') }}</flux:text>
                    </div>

                    <div class="flex items-center gap-2">
                        <flux:icon.signal size="sm" class="text-neutral-400" />
                        <flux:text size="sm" class="text-neutral-500">Status</flux:text>
                        @php
                            $now = now();
                            $start = $assignment->module->datestart;
                            $end = $assignment->module->dateend;
                        @endphp
                        @if ($now->lt($start))
                            <flux:badge color="amber" size="sm">Pending</flux:badge>
                        @elseif ($now->between($start, $end))
                            <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                        @elseif ($now->gt($end))
                            <flux:badge variant="solid" color="lime" size="sm">Completed</flux:badge>
                        @endif
                    </div>
                </div>
            </flux:card>

        </div>

    </div>
</x-layouts::app>