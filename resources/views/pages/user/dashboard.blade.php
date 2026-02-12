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
                    {{ $journalsCount ?? 0 }}
                </flux:text>
            </flux:card>
            
            <flux:card>
                <flux:heading size="lg">Ongoing Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $userTrainings->where('appointed_at', '!=', null)->whereNull('finished_at')->count() }}
                </flux:text>
            </flux:card>
        </div>

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Training Name</flux:table.column>
                    <flux:table.column>Duration</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($userTrainings as $ut)
                        <flux:table.row :key="$ut->id">
                            <flux:table.cell>{{ $ut->training_module?->title ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell>{{ $ut->interval?->format('%a days') ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell>
                                @if($ut->appointed_at === null)
                                    <flux:badge color="gray" size="sm" inset="top bottom">Not Started</flux:badge>
                                @elseif($ut->finished_at !== null)
                                    <flux:badge color="green" size="sm" inset="top bottom">Completed</flux:badge>
                                @else
                                    <flux:badge color="yellow" size="sm" inset="top bottom">Ongoing</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                @if($ut->appointed_at !== null && $ut->finished_at === null)
                                    <flux:button
                                        :href="route('user.documents.create', ['assignment' => $ut->id])"
                                        variant="ghost"
                                        size="sm"
                                        icon="plus"
                                        wire:navigate>
                                        Create Journal
                                    </flux:button>
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