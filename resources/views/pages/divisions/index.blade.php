<x-layouts::app :title="__('Division Units Browser')">
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
                :href="route('divisions.create')"
                size="sm"
                color="teal"
                variant="primary"
                icon="folder-plus">
                Add Division
            </flux:button>
        </div>

        <div class="overflow-x-auto">
            <flux:table :paginate="$divisions">
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Users Count</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($divisions as $division)
                    <flux:table.row :key="$division->id">
                        <flux:table.cell>
                            <a href="{{ route('divisions.show', $division) }}"
                                wire:navigate
                                class="text-sm font-medium hover:underline">
                                {{ $division->division_units }}
                            </a>
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $division->users->count() }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:button
                                variant="primary"
                                color="emerald"
                                :href="route('divisions.show', $division)"
                                size="sm"
                                icon="eye"
                                square />
                            <flux:button
                                :href="route('divisions.edit', $division)"
                                size="sm"
                                color="sky"
                                variant="primary"
                                icon="pencil"
                                square />
                            <form action="{{ route('divisions.destroy', $division) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this division?');">
                                @csrf
                                @method('DELETE')
                                <flux:button
                                    variant="danger"
                                    type="submit"
                                    size="sm"
                                    icon="trash"
                                    square />
                            </form>
                        </flux:table.cell>
                    </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>