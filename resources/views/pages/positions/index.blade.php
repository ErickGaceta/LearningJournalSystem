<x-layouts::app :title="__('Positions Browser')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
        @endif

        <div class="flex flex-col items-end justify-end gap-4 mt-2 me-2">
            <flux:button
                :href="route('positions.create')"
                size="sm"
                color="teal"
                variant="primary"
                icon="folder-plus">
                Add Position
            </flux:button>
        </div>

        <div class="overflow-x-auto">
            <flux:table :paginate="$positions">
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Users Count</flux:table.column>
                    <flux:table.column class="text-right">Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($positions as $position)
                    <flux:table.row :key="$position->id">

                        <flux:table.cell>
                            <a href="{{ route('positions.show', $position) }}"
                                class="text-sm font-medium hover:underline">
                                {{ $position->positions }}
                            </a>
                        </flux:table.cell>

                        <flux:table.cell>
                            <span class="text-sm">
                                {{ $position->users->count() }}
                            </span>
                        </flux:table.cell>

                        <flux:table.cell class="text-right">
                            <div class="flex justify-end gap-2">

                                <flux:tooltip content="View" position="top">
                                    <flux:button
                                        variant="primary"
                                        color="emerald"
                                        :href="route('positions.show', $position)"
                                        size="sm"
                                        icon="eye"
                                        square />
                                </flux:tooltip>

                                <flux:tooltip content="Edit" position="top">
                                    <flux:button
                                        :href="route('positions.edit', $position)"
                                        size="sm"
                                        color="sky"
                                        variant="primary"
                                        icon="pencil"
                                        square />
                                </flux:tooltip>

                                <form action="{{ route('positions.destroy', $position) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this position?');">
                                    @csrf
                                    @method('DELETE')

                                    <flux:tooltip content="Delete" position="top">
                                        <flux:button
                                            variant="danger"
                                            type="submit"
                                            size="sm"
                                            icon="trash"
                                            square />
                                    </flux:tooltip>
                                </form>

                            </div>
                        </flux:table.cell>

                    </flux:table.row>
                    @empty
                    <flux:table.row>
                        <flux:table.cell colspan="3" class="text-center py-8">
                            <span class="text-neutral-500">
                                No positions found.
                                <a href="{{ route('positions.create') }}" class="text-blue-500 hover:underline">
                                    Create one now
                                </a>
                            </span>
                        </flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

        </div>
    </div>
</x-layouts::app>