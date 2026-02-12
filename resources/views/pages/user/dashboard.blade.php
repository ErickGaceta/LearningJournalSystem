<x-layouts::app :title="__('DOST CAR Learning Journal System - User Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Welcome Section -->
        <div class="space-y-1">
            <flux:heading size="xl">Welcome, {{ $user->first_name }}!</flux:heading>
            <flux:subheading>Manage your learning journal entries</flux:subheading>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Assigned Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $userTrainings->count() }}
                </flux:text>
            </flux:card>

            <flux:card>
                <flux:heading size="lg">Journals Created</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $myDocuments }}
                </flux:text>
            </flux:card>

            <flux:card>
                <flux:heading size="lg">Ongoing Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $activeAssignments }}
                </flux:text>
            </flux:card>
        </div>

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Training Name</flux:table.column>
                    <flux:table.column align="center">Duration</flux:table.column>
                    <flux:table.column align="end">Status</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($trainings as $tr)
                    <flux:table.row>
                        <flux:table.cell>{{ $tr->module->title ?? 'N/A' }}</flux:table.cell>
                        <flux:table.cell align="center">
                                {{ $tr->module->datestart->format('M d, Y') . ' - ' . $tr->module->dateend->format('M d, Y') }}
                        </flux:table.cell>
                        <flux:table.cell align="end">
                                @php
                                $now = now();
                                $start = $tr->module->datestart;
                                $end = $tr->module->dateend;
                            @endphp

                            @if ($now->lt($start))
                                <flux:badge color="amber" size="sm">Pending</flux:badge>
                            @elseif ($now->between($start, $end))
                                <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                            @elseif ($now->gt($end))
                                <flux:badge variant="solid" color="lime" size="sm">Completed</flux:badge>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                    @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center py-8">
                            <div class="text-neutral-500">No trainings assigned yet</div>
                        </flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

    </div>
</x-layouts::app>