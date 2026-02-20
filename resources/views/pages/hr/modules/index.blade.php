<x-layouts::app :title="__('Training Modules and Assignments')">
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

        <div class="relative overflow-hidden">
            <form method="GET" action="{{ route('hr.modules.index') }}" class="p-4">
                <div class="flex gap-3 justify-center items-center">
                    <div class="flex-1 relative">
                        <flux:input
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by employee, module, venue, or conductor..."
                            icon:trailing="magnifying-glass"
                            class="w-full rounded-3xl" />
                    </div>
                    <flux:button type="submit" variant="primary" icon="magnifying-glass" color="lime" square />
                    @if(request('search'))
                    <flux:button :href="route('hr.modules.index')" variant="ghost">
                        Clear
                    </flux:button>
                    @endif
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Total Assignments</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $assignments->count() }}
                </flux:text>
            </flux:card>

            <flux:card>
                <flux:heading size="lg">Total Training Hours</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $assignments->sum(fn($a) => $a->module?->hours ?? 0) }} hrs
                </flux:text>
            </flux:card>

            <flux:card>
                <flux:heading size="lg" class="flex gap-1">
                    Assignments This Year
                    <flux:text size="sm">({{ now()->year }})</flux:text>
                </flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ $assignments->filter(fn($a) => $a->created_at->year === now()->year)->count() }}
                </flux:text>
            </flux:card>
        </div>

        <div class="flex flex-col w-full gap-2">
            <div class="flex justify-end">
                <flux:modal.trigger name="create-module">
                    <flux:button size="sm" icon="folder-plus" variant="primary" color="teal">
                        Create New Training
                    </flux:button>
                </flux:modal.trigger>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block">
                <flux:table :paginate="$trainingModules">
                    <flux:table.columns>
                        <flux:table.column>Module Title</flux:table.column>
                        <flux:table.column>Training Hours</flux:table.column>
                        <flux:table.column>Start - End</flux:table.column>
                        <flux:table.column>Venue</flux:table.column>
                        <flux:table.column>Sponsor / Conductor</flux:table.column>
                        <flux:table.column>Registration Fee</flux:table.column>
                        <flux:table.column>Assignees</flux:table.column>
                        <flux:table.column>Actions</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($trainingModules as $tm)
                        <flux:table.row>
                            <flux:table.cell>{{ $tm->title }}</flux:table.cell>
                            <flux:table.cell>{{ $tm->hours }} hrs</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap">
                                {{ $tm->datestart->format('M d, Y') }} — {{ $tm->dateend->format('M d, Y') }}
                            </flux:table.cell>
                            <flux:table.cell>{{ $tm->venue }}</flux:table.cell>
                            <flux:table.cell>{{ $tm->conductedby }}</flux:table.cell>
                            <flux:table.cell>{{ $tm->registration_fee ?: '—' }}</flux:table.cell>
                            <flux:table.cell>
                                @if($tm->assignments->isEmpty())
                                <flux:text variant="subtle" size="sm">—</flux:text>
                                @else
                                <div class="flex flex-wrap gap-1">
                                    @foreach($tm->assignments as $assignment)
                                    <flux:modal.trigger name="unassign-{{ $assignment->id }}">
                                        <flux:badge
                                            color="zinc"
                                            size="sm"
                                            icon-trailing="x-mark"
                                            class="cursor-pointer hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-950/40 dark:hover:text-red-400 transition-colors">
                                            {{ $assignment->employee_name }}
                                        </flux:badge>
                                    </flux:modal.trigger>
                                    @endforeach
                                </div>
                                @endif
                            </flux:table.cell>

                            <flux:table.cell align="right">
                                <div class="flex items-center justify-end gap-1">
                                    <!-- Assign -->
                                    <flux:modal.trigger name="assign-module-{{ $tm->id }}">
                                        <flux:button size="sm" color="teal" variant="ghost" icon="user-plus" square />
                                    </flux:modal.trigger>

                                    <!-- Edit -->
                                    <flux:modal.trigger name="edit-module-{{ $tm->id }}">
                                        <flux:button size="sm" color="sky" variant="ghost" icon="eye" square />
                                    </flux:modal.trigger>

                                    <!-- Delete -->
                                    <flux:modal.trigger name="delete-module-{{ $tm->id }}">
                                        <flux:button variant="ghost" size="sm" icon="trash" square />
                                    </flux:modal.trigger>

                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                        @empty
                        <flux:table.row>
                            <flux:table.cell colspan="8" class="text-center py-8 text-neutral-500">
                                No training modules yet.
                            </flux:table.cell>
                        </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden space-y-4">
            @forelse($trainingModules as $tm)
            <flux:card class="p-4 bg-transparent">
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <flux:heading>{{ $tm->title }}</flux:heading>
                        <div class="flex gap-2">
                            <flux:modal.trigger name="assign-module-{{ $tm->id }}">
                                <flux:button size="sm" color="teal" variant="ghost" icon="user-plus" square />
                            </flux:modal.trigger>
                            <flux:modal.trigger name="edit-module-{{ $tm->id }}">
                                <flux:button size="sm" color="sky" variant="ghost" icon="eye" square />
                            </flux:modal.trigger>
                            <flux:modal.trigger name="delete-module-{{ $tm->id }}">
                                <flux:button variant="ghost" size="sm" icon="trash" square />
                            </flux:modal.trigger>
                        </div>
                    </div>

                    <flux:separator />

                    <div class="flex gap-2 text-sm text-neutral-500">
                        Venue: <flux:text variant="subtle">{{ $tm->venue }}</flux:text>
                    </div>
                    <div class="flex gap-2 text-sm text-neutral-500">
                        Conducted by: <flux:text variant="subtle">{{ $tm->conductedby }}</flux:text>
                    </div>
                    <div class="flex gap-2 text-sm text-neutral-500">
                        Fee: <flux:text variant="subtle">{{ $tm->registration_fee ?: '—' }}</flux:text>
                    </div>
                    <div class="flex gap-2 text-sm text-neutral-500">
                        Duration: <flux:text variant="subtle">
                            {{ $tm->datestart->format('M d, Y') }} — {{ $tm->dateend->format('M d, Y') }}
                        </flux:text>
                    </div>
                    <div class="flex gap-2 text-sm text-neutral-500">
                        Hours: <flux:text variant="subtle">{{ $tm->hours }} hrs</flux:text>
                    </div>
                    <div class="flex gap-2 text-sm text-neutral-500">
                        Assignees:

                        @if($tm->assignments->isEmpty())
                        <flux:text variant="subtle" size="sm">—</flux:text>
                        @else
                        <div class="flex flex-wrap gap-1">
                            @foreach($tm->assignments as $assignment)
                            <flux:modal.trigger name="unassign-{{ $assignment->id }}">
                                <flux:badge
                                    color="zinc"
                                    size="sm"
                                    icon-trailing="x-mark"
                                    class="cursor-pointer hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-950/40 dark:hover:text-red-400 transition-colors">
                                    {{ $assignment->employee_name }}
                                </flux:badge>
                            </flux:modal.trigger>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </flux:card>
            @empty
            <div class="text-center py-8 text-neutral-500">No training modules yet.</div>
            @endforelse
        </div>
    </div>

    <x-hr.create-module-modal />

    {{-- Per-module modals --}}
    @foreach($trainingModules as $tm)
    <x-hr.module-modals :module="$tm" :users="$users" />
    <x-hr.edit-module-modal :module="$tm" />

    {{-- Unassign modals --}}
    @foreach($tm->assignments as $assignment)
    <x-hr.unassign-modal :assignment="$assignment" />
    @endforeach
    @endforeach
</x-layouts::app>