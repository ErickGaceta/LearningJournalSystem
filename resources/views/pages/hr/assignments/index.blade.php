<x-layouts::app :title="__('Training Modules')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm">
            <div class="flex items-center">
                <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        <!-- Search Bar -->
        <div class="relative overflow-hidden">
            <form method="GET" action="{{ route('hr.assignments.index') }}" class="p-4">
                <div class="flex gap-3 justify-center items-center">
                    <div class="flex-1 relative">
                        <flux:input
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by employee, module, or venue..."
                            icon:trailing="magnifying-glass"
                            class="w-full rounded-3xl" />
                    </div>
                    <flux:button type="submit" variant="primary" icon="magnifying-glass" color="lime" square />
                    @if(request('search'))
                    <flux:button
                        :href="route('hr.assignments.index')"
                        variant="ghost">
                        Clear
                    </flux:button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Total Assignments</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $assignments->total() ?? $assignments->count() }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Total Training Hours</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $totalHours ?? $assignments->sum(fn($a) => $a->module->hours) }} hrs
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg" class="flex">Assignments This Year
                    <flux:text size="sm">({{ now()->year }})</flux:text>
                </flux:heading>
                {{ $totalYearlyAssignments ?? '' }}
                <flux:text class="mt-2 mb-4"></flux:text>
            </flux:card>
        </div>

        @if($assignments->count() > 0)

        <!-- Top Action Row -->
        <div class="flex justify-end">
            <flux:button :href="route('hr.assignments.create')" size="sm" icon="folder-plus" variant="primary" color="teal">Assign Training</flux:button>
        </div>

        <!-- Assignments Table (Desktop) -->
        <div class="relative overflow-hidden hidden lg:block">
            <div class="overflow-x-auto">
                <flux:table :paginate="$assignments">
                    <flux:table.columns sticky>
                        <flux:table.column>Employee Name</flux:table.column>
                        <flux:table.column>Assigned Module</flux:table.column>
                        <flux:table.column>Venue</flux:table.column>
                        <flux:table.column>Duration</flux:table.column>
                        <flux:table.column>Training Hours</flux:table.column>
                        <flux:table.column>Actions</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach($assignments as $as)
                        <flux:table.row :key="$as->id">

                            <flux:table.cell>
                                <span class="text-sm font-medium">{{ $as->employee_name }}</span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-sm truncate max-w-xs block">{{ $as->module->title }}</span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-sm truncate max-w-xs block">{{ $as->module->venue }}</span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-sm whitespace-nowrap">
                                    {{ $as->module->datestart->format('M d, Y') }}
                                    -
                                    {{ $as->module->dateend->format('M d, Y') }}
                                </span>
                            </flux:table.cell>

                            <flux:table.cell>
                                <span class="text-sm">{{ $as->module->hours }} hrs</span>
                            </flux:table.cell>

                            <flux:table.cell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <!-- Unassign Button with Modal -->
                                    <flux:modal.trigger name="delete-position-{{ $as->id }}">
                                        <flux:button variant="primary" color="red" size="sm">
                                            Unassign
                                        </flux:button>
                                    </flux:modal.trigger>
                                </div>
                            </flux:table.cell>

                        </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </div>

        <!-- Assignments Cards (Mobile) -->
        <div class="lg:hidden space-y-4">
            @foreach($assignments as $as)
            <flux:card class="p-4 bg-transparent">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between align-center items-center">
                        <flux:heading>{{ $as->employee_name }}</flux:heading>
                        <flux:modal.trigger name="delete-position-{{ $as->id }}">
                            <flux:button variant="primary" color="red" size="sm">
                                Unassign
                            </flux:button>
                        </flux:modal.trigger>
                    </div>

                    <flux:separator />

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Module: <flux:text variant="subtle">{{ $as->module->title }}</flux:text>
                    </div>

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Venue: <flux:text variant="subtle">{{ $as->module->venue }}</flux:text>
                    </div>

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Duration: <flux:text variant="subtle">
                            {{ $as->module->datestart->format('M d, Y') }}
                            - {{ $as->module->dateend->format('M d, Y') }}
                        </flux:text>
                    </div>

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Hours: <flux:text variant="subtle">{{ $as->module->hours }} hrs</flux:text>
                    </div>
                </div>
            </flux:card>
            @endforeach
        </div>

        @else

        <!-- Empty State -->
        <div class="flex justify-end">
            <flux:button :href="route('hr.assignments.create')" size="sm" icon="folder-plus" variant="primary" color="teal">Assign Training</flux:button>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="flex flex-col items-center justify-center p-16 text-center">
                <div class="bg-neutral-100 dark:bg-neutral-800 w-20 h-20 rounded-full flex items-center justify-center mb-6">
                    <flux:icon.folder class="size-10 text-neutral-400" />
                </div>
                <h3 class="text-xl font-semibold text-heading mb-3">No Assignments Yet</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-6">Assign a training module to get started</p>
                <flux:button :href="route('hr.assignments.create')" size="sm" icon="folder-plus" variant="primary" color="teal">Assign Training</flux:button>
            </div>
        </div>

        @endif

        <!-- Delete Modals (rendered outside table for all rows) -->
        @foreach($assignments as $as)
        <flux:modal name="delete-position-{{ $as->id }}" class="max-w-md">
            <form action="{{ route('hr.assignments.destroy', $as) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="p-2 bg-white dark:bg-neutral-800">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                        <flux:icon.exclamation-triangle class="w-8 h-8 text-red-500" />
                    </div>
                </div>

                <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
                    <flux:heading size="lg" class="text-center text-zinc-900 dark:text-white">
                        Unassign Training?
                    </flux:heading>

                    <div class="rounded-lg p-4 shadow-sm">
                        <flux:text size="sm" class="text-zinc-900 dark:text-white text-center">
                            You are about to unassign:
                        </flux:text>
                        <flux:text size="lg" class="font-semibold text-zinc-900 dark:text-white text-center mt-2">
                            {{ $as->employee_name }} — {{ $as->module->title }}
                        </flux:text>
                    </div>

                    <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4 shadow-sm">
                        <div class="flex flex-col items-center gap-2">
                            <flux:icon.information-circle class="w-5 h-5 text-red-500 dark:text-red-400" />
                            <flux:text size="sm" class="text-zinc-900 dark:text-white text-center">
                                <strong class="font-semibold text-red-500">Warning:</strong> This action cannot be undone. All associated data will be permanently deleted.
                            </flux:text>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2">
                    <flux:modal.close>
                        <flux:button variant="ghost" size="sm" class="flex-1">
                            Cancel
                        </flux:button>
                    </flux:modal.close>

                    <flux:button
                        type="submit"
                        variant="primary"
                        color="red"
                        size="sm"
                        class="flex-1">
                        Unassign Permanently
                    </flux:button>
                </div>
            </form>
        </flux:modal>
        @endforeach

    </div>
</x-layouts::app>
