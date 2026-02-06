<x-layouts::app :title="__('DOST CAR Learning Journal System - User Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Welcome Section -->
        <div class="space-y-1">
            <flux:heading size="xl">Welcome, {{ auth()->user()->first_name }}!</flux:heading>
            <flux:subheading>Manage your learning journal entries</flux:subheading>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Assigned Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->count() }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Journals Created</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->sum('hours') }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Ongoing Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->whereYear('datestart', date('Y'))->count() }}
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


             @forelse($users as $user)
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell>{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }}</flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                    <flux:table.cell><flux:badge color="green" size="sm" inset="top bottom">Completed</flux:badge></flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                    <flux:table.cell></flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
            @empty
            <flux:table.rows>
                <flux:table.row>
                    <flux:table.cell class="col-span-6">No users in the database</flux:table.cell>
                </flux:table.row>
            </flux:table.rows>
            @endforelse
        </flux:table>
        <flux:button
                    :href="route('dashboard')"
                    icon="plus"
                    wire:navigate>
                    Create First Journal
                </flux:button>
    </div>

    </div>
</x-layouts::app>
