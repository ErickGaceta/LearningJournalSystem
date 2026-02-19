<x-layouts::app :title="__('Positions Browser')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        @if(session('success'))
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg max-w-md">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="shrink-0 ml-4 text-white hover:text-green-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg max-w-md">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="flex-1">{{ session('error') }}</span>
                <button @click="show = false" class="shrink-0 ml-4 text-white hover:text-red-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <div class="flex flex-col items-end justify-end gap-4 mt-2 me-2">
            <flux:modal.trigger name="position-create">
                <flux:button size="sm" color="teal" variant="primary" icon="folder-plus">
                    Add Position
                </flux:button>
            </flux:modal.trigger>
        </div>

        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Users Count</flux:table.column>
                    <flux:table.column align="end">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($positions as $position)
                    <flux:table.row :key="$position->id">
                        <flux:table.cell>
                            <flux:modal.trigger name="position-show-{{ $position->id }}">
                                <button class="text-sm font-medium hover:underline text-left">
                                    {{ $position->positions }}
                                </button>
                            </flux:modal.trigger>
                        </flux:table.cell>

                        <flux:table.cell>
                            <span class="text-sm">{{ $position->users->count() }}</span>
                        </flux:table.cell>

                        <flux:table.cell align="end">
                            <flux:modal.trigger name="position-show-{{ $position->id }}">
                                <flux:button variant="ghost" color="emerald" size="sm" icon="eye" square />
                            </flux:modal.trigger>

                            <flux:modal.trigger name="position-delete-{{ $position->id }}">
                                <flux:button variant="ghost" size="sm" icon="trash" square />
                            </flux:modal.trigger>
                        </flux:table.cell>
                    </flux:table.row>
                    @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center py-8">
                            <span class="text-neutral-500">No positions found.</span>
                        </flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $positions->links() }}
            </div>
        </div>
    </div>

    {{-- Create --}}
    <x-crud-modal
        name="position"
        mode="create"
        title="Create New Position"
        subtitle="Add a new position to the system"
        :action="route('admin.positions.store')"
        :fields="[
            ['label' => 'Position Name', 'name' => 'positions', 'placeholder' => 'Position Name', 'required' => true],
        ]" />

    @foreach($positions as $position)

    <x-crud-modal
        name="position"
        mode="show"
        :model="$position"
        :title="$position->positions"
        subtitle="Position Details"
        :action="route('admin.positions.update', $position)"
        :fields="[
            ['label' => 'Position Name', 'name' => 'positions', 'placeholder' => 'Position Name', 'value' => $position->positions, 'required' => true],
        ]"
        :details="[
            ['label' => 'Position Name',   'value' => $position->positions],
            ['label' => 'Number of Users', 'value' => $position->users->count()],
            ['label' => 'Created At',      'value' => $position->created_at->format('M d, Y h:i A')],
            ['label' => 'Last Updated',    'value' => $position->updated_at->format('M d, Y h:i A')],
        ]" />

    <x-crud-modal
        name="position"
        mode="delete"
        :model="$position"
        title="Delete Position?"
        :action="route('admin.positions.destroy', $position)"
        :deleteLabel="$position->positions" />

    @endforeach

</x-layouts::app>