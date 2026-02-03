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
            <a href="{{ route('positions.create') }}"
                class="bg-blue-500 text-white px-3 py-2 rounded-xl hover:bg-blue-600 transition-colors inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Position
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                            Users Count
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-neutral-600 dark:text-neutral-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-transparent dark:bg-grey-900 divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($positions as $position)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors" x-data="{ showDeleteModal: false }">
                        <td class="px-6 py-4">
                            <a href="{{ route('positions.show', $position) }}" class="text-sm font-medium text-heading hover:text-blue-500 transition-colors">
                                {{ $position->positions }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-neutral-600">{{ $position->users->count() }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('positions.show', $position) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-lg transition-colors"
                                    title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                                <a href="{{ route('positions.edit', $position) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-neutral-200 hover:bg-neutral-300 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-heading text-xs font-medium rounded-lg transition-colors"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                
                                <!-- Delete Button - Opens Modal -->
                                <button type="button" 
                                    @click="showDeleteModal = true"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg transition-colors"
                                    title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div x-show="showDeleteModal" 
                                     x-cloak
                                     @click.self="showDeleteModal = false"
                                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0">
                                    
                                    <div @click.away="showDeleteModal = false"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform scale-90"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-90"
                                         class="bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
                                        
                                        <!-- Modal Header with Icon -->
                                        <div class="bg-white dark:bg-neutral-800 p-6">
                                            <div class="flex items-center justify-center w-16 h-16 mx-auto">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
                                            <h3 class="text-2xl font-bold text-center text-white">
                                                Delete Position?
                                            </h3>
                                            
                                            <div class="bg-neutral-100 dark:bg-neutral-700 rounded-lg p-4">
                                                <p class="text-sm text-white text-center">
                                                    You are about to delete:
                                                </p>
                                                <p class="text-lg font-semibold text-white text-center mt-2">
                                                    {{ $position->positions }}
                                                </p>
                                            </div>

                                            <div class="bg-neutral-100 dark:bg-neutral-700 rounded-lg p-4">
                                                <div class="flex gap-3">
                                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <p class="text-sm text-red-500 text-center">
                                                        <strong class="font-semibold text-red-500">Warning:</strong> This action cannot be undone. All associated data will be permanently deleted.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2">
                                            <button type="button" 
                                                @click="showDeleteModal = false"
                                                class="flex-1 px-3 py-1.5 bg-neutral-200 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300 text-sm font-medium rounded-lg hover:bg-neutral-300 dark:hover:bg-neutral-600 transition-colors">
                                                Cancel
                                            </button>
                                            
                                            <form action="{{ route('positions.destroy', $position) }}" method="POST" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors shadow-lg hover:shadow-xl">
                                                    Delete Permanently
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-neutral-500">
                            No positions found. <a href="{{ route('positions.create') }}" class="text-blue-500 hover:underline">Create one now</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-layouts::app>