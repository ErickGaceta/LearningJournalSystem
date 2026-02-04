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

                        <flux:table.cell class="text-right">
                            <div class="flex justify-end gap-2">

                                <flux:tooltip content="View" position="top">
                                    <flux:button
                                        variant="ghost"
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
                                        variant="ghost"
                                        icon="pencil"
                                        square />
                                </flux:tooltip>

                                <!-- Delete Button with Modal -->
                                <flux:tooltip content="Delete" position="top">
                                    <flux:modal.trigger name="delete-position-{{ $position->id }}">
                                        <flux:button
                                            variant="ghost"
                                            size="sm"
                                            icon="trash"
                                            square />
                                    </flux:modal.trigger>
                                </flux:tooltip>

                                <!-- Delete Confirmation Modal using Flux -->
                                <flux:modal name="delete-position-{{ $position->id }}" class="max-w-md">
                                    <!-- Modal Header with Icon -->
                                    <div class="bg-white dark:bg-neutral-800 p-6">
                                        <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-500 rounded-full shadow-lg">
                                            <flux:icon.exclamation-triangle class="w-8 h-8 text-white" />
                                        </div>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
                                        <flux:heading size="lg" class="text-center text-white">
                                            Delete Position?
                                        </flux:heading>
                                        
                                        <div class="bg-neutral-100 dark:bg-neutral-700 rounded-lg p-4">
                                            <flux:text size="sm" class="text-white text-center">
                                                You are about to delete:
                                            </flux:text>
                                            <flux:text size="lg" class="font-semibold text-white text-center mt-2">
                                                {{ $position->positions }}
                                            </flux:text>
                                        </div>

                                        <div class="bg-neutral-100 dark:bg-neutral-700 border border-neutral-200 dark:border-neutral-600 rounded-lg p-4">
                                            <div class="flex flex-col items-center gap-2">
                                                <flux:icon.information-circle class="w-5 h-5 text-red-500" />
                                                <flux:text size="sm" class="text-white text-center">
                                                    <strong class="font-semibold text-red-500">Warning:</strong> This action cannot be undone. All associated data will be permanently deleted.
                                                </flux:text>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal Footer -->
                                    <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2">
                                        <flux:modal.close>
                                            <flux:button variant="ghost" size="sm" class="flex-1">
                                                Cancel
                                            </flux:button>
                                        </flux:modal.close>
                                        
                                        <form action="{{ route('positions.destroy', $position) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button 
                                                type="submit" 
                                                variant="danger" 
                                                size="sm" 
                                                class="w-full">
                                                Delete Permanently
                                            </flux:button>
                                        </form>
                                    </div>
                                </flux:modal>

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

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-layouts::app>