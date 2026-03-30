<x-layouts::app :title="__('Positions Browser')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

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
            <x-pagination :paginator="$positions" />
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