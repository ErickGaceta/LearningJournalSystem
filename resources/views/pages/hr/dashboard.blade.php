<x-layouts::app :title="__('DOST CAR Learning Journal System - HR Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Welcome Section -->
        <div class="space-y-1">
            <flux:heading size="xl">Welcome, {{ auth()->user()->first_name }}!</flux:heading>
            <flux:subheading>Monitor trainings, users, and learning journal entries</flux:subheading>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Total Users on Training</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $usersInTraining ?? 0 }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Total Modules / Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $totalModules ?? 0 }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Active Trainings</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $activeTraining ?? 0 }}
                </flux:text>
            </flux:card>
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Training Name</flux:table.column>
                    <flux:table.column>Assigned Users</flux:table.column>
                    <flux:table.column align="center">Duration</flux:table.column>
                    <flux:table.column align="end">Status</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($modules ?? [] as $module)
                    <flux:table.row>
                        <flux:table.cell>{{ $module->title ?? 'N/A' }}</flux:table.cell>
                        <flux:table.cell>
                            {{ $module->assignments->count() ?? 0 }} user(s)
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            {{ $module->datestart->format('M d, Y') . ' - ' . $module->dateend->format('M d, Y') }}
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            @php
                                $now = now();
                                $start = $module->datestart;
                                $end = $module->dateend;
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
                            <div class="text-neutral-500">No training modules found</div>
                        </flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>



        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            @forelse($modules ?? [] as $module)
            @php
                $now = now();
                $start = $module->datestart;
                $end = $module->dateend;
            @endphp

            <flux:card class="p-4 bg-transparent">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold">{{ $module->title ?? 'N/A' }}</span>
                        <div>
                            @if ($now->lt($start))
                                <flux:badge color="amber" size="sm">Pending</flux:badge>
                            @elseif ($now->between($start, $end))
                                <flux:badge color="lime" size="sm">Ongoing</flux:badge>
                            @elseif ($now->gt($end))
                                <flux:badge variant="solid" color="lime" size="sm">Completed</flux:badge>
                            @endif
                        </div>
                    </div>

                    <flux:separator />

                    <div class="text-sm text-neutral-500 flex gap-2">
                        Dates: <flux:text variant="subtle">{{ $module->datestart->format('M d, Y') }} - {{ $module->dateend->format('M d, Y') }}</flux:text>
                    </div>

                    <div class="text-sm text-neutral-500 flex gap-2">
                        Assigned Users: <flux:text variant="subtle">{{ $module->assignments->count() }} user(s)</flux:text>
                    </div>

                    <div class="flex justify-between items-center mt-2">
                        <flux:button
                            :href="route('hr.modules.show', $module)"
                            variant="ghost"
                            size="sm"
                            wire:navigate>
                            View Details
                        </flux:button>
                    </div>
                </div>
            </flux:card>
            @empty
            <div class="text-center py-8 text-neutral-500">No training modules found</div>
            @endforelse
        </div>

    </div>
</x-layouts::app>
