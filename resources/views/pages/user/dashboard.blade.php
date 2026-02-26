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
            $now = now();
            $rows = $trainings->map(fn($tr) => [
                'title' => $tr->module->title,
                'start' => $tr->module->datestart,
                'end'   => $tr->module->dateend,
                'badge' => $now->lt($tr->module->datestart)
                    ? ['color' => 'amber', 'label' => 'Pending']
                    : ($now->between($tr->module->datestart, $tr->module->dateend)
                        ? ['color' => 'lime',  'label' => 'Ongoing']
                        : ['color' => 'zinc',  'label' => 'Completed']),
            ]);
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
                    @forelse($rows as $r)
                        <flux:table.row>
                            <flux:table.cell>{{ $r['title'] }}</flux:table.cell>
                            <flux:table.cell align="center" class="whitespace-nowrap">
                                {{ $r['start']->format('M d, Y') }} — {{ $r['end']->format('M d, Y') }}
                            </flux:table.cell>
                            <flux:table.cell align="center">
                                <flux:badge color="{{ $r['badge']['color'] }}" size="sm">{{ $r['badge']['label'] }}</flux:badge>
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
            @forelse($rows as $r)
                <flux:card class="p-4 bg-transparent">
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold">{{ $r['title'] }}</span>
                            <flux:badge color="{{ $r['badge']['color'] }}" size="sm">{{ $r['badge']['label'] }}</flux:badge>
                        </div>

                        <flux:separator />

                        <div class="text-sm text-neutral-500 flex gap-2">
                            Dates: <flux:text variant="subtle">{{ $r['start']->format('M d, Y') }} — {{ $r['end']->format('M d, Y') }}</flux:text>
                        </div>
                    </div>
                </flux:card>
            @empty
                <div class="text-center py-8 text-neutral-500">No trainings assigned yet</div>
            @endforelse
        </div>

    </div>
</x-layouts::app>