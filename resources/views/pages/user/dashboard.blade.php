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
                </flux:text>
            </flux:card>

            <flux:card>
                <flux:heading size="lg">Journals Created</flux:heading>
                <flux:text class="mt-2 mb-4">
                </flux:text>
            </flux:card>
            
            <flux:card>
                <flux:heading size="lg">Ongoing Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                </flux:text>
            </flux:card>
        </div>

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Training Name</flux:table.column>
                    <flux:table.column>Duration</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>


                @forelse($userTrainings as $ut)
                <flux:table.rows>
                    <flux:table.row>
                        <flux:table.cell>{{ $ut->training_module?->title ?? 'N/A' }}</flux:table.cell>
                        <flux:table.cell>{{ $ut->interval?->format('%a days') ?? 'N/A' }}</flux:table.cell>
                        <flux:table.cell>

                            @if($ut->appointed_at === null)
                            <flux:badge color="gray" size="sm" inset="top bottom">N/A</flux:badge>
                            @elseif($ut->finished_at !== null)
                            <flux:badge color="green" size="sm" inset="top bottom">Completed</flux:badge>
                            @else
                            <flux:badge color="yellow" size="sm" inset="top bottom">Ongoing</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
                @empty
                <flux:table.rows>
                    <flux:table.row>
                        <flux:table.cell class="col-span-6">No Trainings yet</flux:table.cell>
                    </flux:table.row>
                </flux:table.rows>
                @endforelse
            </flux:table>
            <flux:button
                :href="route('user.documents.create')"
                icon="plus"
                wire:navigate>
                Create First Journal
            </flux:button>
        </div>

    </div>
</x-layouts::app>