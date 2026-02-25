<x-layouts::app :title="__('DOST CAR Learning Journal System - User Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="space-y-1">
            <flux:heading size="xl">Welcome, {{ $user->first_name }}!</flux:heading>
            <flux:subheading>Manage your learning journal entries</flux:subheading>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Assigned Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">{{ $userTrainings->count() }}</flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Journals Created</flux:heading>
                <flux:text class="mt-2 mb-4">{{ $myDocuments }}</flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Ongoing Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">{{ $activeAssignments }}</flux:text>
            </flux:card>
        </div>

        @php
            $statusBadge = function (bool $isPending, bool $isOngoing): array {
                if ($isPending) return ['color' => 'amber', 'label' => 'Pending'];
                if ($isOngoing) return ['color' => 'lime',  'label' => 'Ongoing'];
                return                 ['color' => 'zinc',  'label' => 'Completed'];
            };
        @endphp

        {{-- Desktop --}}
        <div class="hidden lg:block">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Training Name</flux:table.column>
                    <flux:table.column align="center">Duration</flux:table.column>
                    <flux:table.column align="center">Status</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($trainings as $tr)
                        @php
                            $now   = now();
                            $start = $tr->module->datestart;
                            $end   = $tr->module->dateend;
                            $badge = $statusBadge($now->lt($start), $now->between($start, $end));
                        @endphp
                        <flux:table.row>
                            <flux:table.cell>{{ $tr->module->title }}</flux:table.cell>
                            <flux:table.cell align="center" class="whitespace-nowrap">
                                {{ $start->format('M d, Y') }} — {{ $end->format('M d, Y') }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                <flux:badge color="{{ $badge['color'] }}" size="sm">{{ $badge['label'] }}</flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center py-8 text-neutral-500">
                                No trainings assigned yet
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        {{-- Mobile --}}
        <div class="lg:hidden space-y-4">
            @forelse($trainings as $tr)
                @php
                    $now   = now();
                    $start = $tr->module->datestart;
                    $end   = $tr->module->dateend;
                    $badge = $statusBadge($now->lt($start), $now->between($start, $end));
                @endphp
                <flux:card class="p-4 bg-transparent">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold">{{ $tr->module->title }}</span>
                            <flux:badge color="{{ $badge['color'] }}" size="sm">{{ $badge['label'] }}</flux:badge>
                        </div>

                        <flux:separator />

                        <div class="text-sm text-neutral-500 flex gap-2">
                            Dates: <flux:text variant="subtle">{{ $start->format('M d, Y') }} — {{ $end->format('M d, Y') }}</flux:text>
                        </div>
                    </div>
                </flux:card>
            @empty
                <div class="text-center py-8 text-neutral-500">No trainings assigned yet</div>
            @endforelse
        </div>

    </div>
</x-layouts::app>