<x-layouts::app :title="__('Positions Browser')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Success/Error Messages with Animation -->
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
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="flex-shrink-0 ml-4 text-white hover:text-green-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="flex-1">{{ session('error') }}</span>
                <button @click="show = false" class="flex-shrink-0 ml-4 text-white hover:text-red-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
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
                    <flux:table.column align="end">Actions</flux:table.column>
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

                        <flux:table.cell align="end">
                            <flux:button
                                variant="ghost"
                                color="emerald"
                                :href="route('positions.show', $position)"
                                size="sm"
                                icon="eye"
                                square />
                            <flux:button
                                :href="route('positions.edit', $position)"
                                size="sm"
                                color="sky"
                                variant="ghost"
                                icon="pencil"
                                square />

                            <!-- Delete Button with Modal -->
                            <flux:modal.trigger name="delete-position-{{ $position->id }}">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    square />
                            </flux:modal.trigger>

                            <!-- Delete Confirmation Modal using Flux -->
                            <flux:modal name="delete-position-{{ $position->id }}" class="">
                                <form action="{{ route('positions.destroy', $position) }}" method="POST" class="flex flex-col items-center max-w-md">
                                    @csrf
                                    @method('DELETE')

                                    <div>
                                        <div class="flex flex-col items-center gap-3 mb-4">
                                            <flux:heading size="lg">Delete Division</flux:heading>
                                            <flux:icon.exclamation-triangle class="text-red-600" />
                                            <flux:subheading icon="exclamation-triangle">This action cannot be undone</flux:subheading>
                                        </div>
                                    </div>

                                    <flux:text class="mb-6">
                                        Are you sure you want to delete <strong>"{{ $position->positions }}"</strong>?
                                        All data associated with this position will be permanently removed.
                                    </flux:text>

                                    <div class="flex gap-3 justify-end">
                                        <flux:modal.close>
                                            <flux:button variant="ghost">Cancel</flux:button>
                                        </flux:modal.close>

                                        <flux:button type="submit" variant="primary" color="red">
                                            Delete Division
                                        </flux:button>
                                    </div>
                                </form>
                            </flux:modal>
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

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-layouts::app>