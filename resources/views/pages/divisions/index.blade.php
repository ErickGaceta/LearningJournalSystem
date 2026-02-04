<x-layouts::app :title="__('Division Units Browser')">
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
                    <flux:table.column align="end">Actions</flux:table.column>
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

                        <flux:table.cell align="end">
                            <flux:button
                                variant="ghost"
                                color="emerald"
                                :href="route('divisions.show', $division)"
                                size="sm"
                                icon="eye"
                                square />
                            <flux:button
                                :href="route('divisions.edit', $division)"
                                size="sm"
                                color="sky"
                                variant="ghost"
                                icon="pencil"
                                square />

                            <!-- Delete Button with Modal -->
                            <flux:modal.trigger name="delete-division-{{ $division->id }}">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    square />
                            </flux:modal.trigger>

                            <!-- Delete Confirmation Modal using Flux -->
                            <flux:modal name="delete-division-{{ $division->id }}" class="max-w-md adaptive-modal">
                                <!-- Modal Header with Icon -->
                                <div class="adaptive-modal-header p-6">
                                    <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-500 rounded-full shadow-lg">
                                        <flux:icon.exclamation-triangle class="w-8 h-8 text-white" />
                                    </div>
                                </div>

                                <!-- Modal Body -->
                                <div class="p-6 space-y-4 adaptive-modal-body">
                                    <flux:heading size="lg" class="text-center adaptive-modal-text">
                                        Delete Division/Unit?
                                    </flux:heading>
                                    
                                    <div class="adaptive-modal-highlight rounded-lg p-4">
                                        <flux:text size="sm" class="adaptive-modal-text text-center">
                                            You are about to delete:
                                        </flux:text>
                                        <flux:text size="lg" class="font-semibold adaptive-modal-text text-center mt-2">
                                            {{ $division->division_units }}
                                        </flux:text>
                                    </div>

                                    <div class="adaptive-modal-warning border rounded-lg p-4">
                                        <div class="flex flex-col items-center gap-2">
                                            <flux:icon.information-circle class="w-5 h-5 text-red-500" />
                                            <flux:text size="sm" class="adaptive-modal-text text-center">
                                                <strong class="font-semibold text-red-500">Warning:</strong> This action cannot be undone. All associated data will be permanently deleted.
                                            </flux:text>
                                        </div>
                                    </div>
                                </div>

                                <div class="adaptive-modal-footer px-6 py-3 flex gap-2">
                                    <flux:modal.close>
                                        <flux:button variant="ghost" size="sm" class="flex-1">
                                            Cancel
                                        </flux:button>
                                    </flux:modal.close>
                                    
                                    <form action="{{ route('divisions.destroy', $division) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button 
                                            type="submit" 
                                            variant="danger" 
                                            size="sm" 
                                            class="w-full">
                                            Delete Permanently
                                        </flux:button>
                                    </div>
                                </form>
                            </flux:modal>
                        </flux:table.cell>
                    </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }

        /* Adaptive Modal Styles - Light Background */
        body.bg-light .adaptive-modal-header,
        body.bg-light .adaptive-modal-body,
        body.bg-light .adaptive-modal-footer {
            background-color: #ffffff !important;
        }

        body.bg-light .adaptive-modal-text {
            color: #18181b !important;
        }

        body.bg-light .adaptive-modal-highlight {
            background-color: #f4f4f5 !important;
        }

        body.bg-light .adaptive-modal-warning {
            background-color: #fef2f2 !important;
            border-color: #fecaca !important;
        }

        /* Adaptive Modal Styles - Dark Background */
        body.bg-dark .adaptive-modal-header,
        body.bg-dark .adaptive-modal-body,
        body.bg-dark .adaptive-modal-footer {
            background-color: #27272a !important;
        }

        body.bg-dark .adaptive-modal-text {
            color: #ffffff !important;
        }

        body.bg-dark .adaptive-modal-highlight {
            background-color: #3f3f46 !important;
        }

        body.bg-dark .adaptive-modal-warning {
            background-color: #3f1a1a !important;
            border-color: #5c2626 !important;
        }

        /* Adaptive Modal Styles - System Default (Light) */
        @media (prefers-color-scheme: light) {
            body.bg-system .adaptive-modal-header,
            body.bg-system .adaptive-modal-body,
            body.bg-system .adaptive-modal-footer {
                background-color: #ffffff !important;
            }

            body.bg-system .adaptive-modal-text {
                color: #18181b !important;
            }

            body.bg-system .adaptive-modal-highlight {
                background-color: #f4f4f5 !important;
            }

            body.bg-system .adaptive-modal-warning {
                background-color: #fef2f2 !important;
                border-color: #fecaca !important;
            }
        }

        /* Adaptive Modal Styles - System Default (Dark) */
        @media (prefers-color-scheme: dark) {
            body.bg-system .adaptive-modal-header,
            body.bg-system .adaptive-modal-body,
            body.bg-system .adaptive-modal-footer {
                background-color: #27272a !important;
            }

            body.bg-system .adaptive-modal-text {
                color: #ffffff !important;
            }

            body.bg-system .adaptive-modal-highlight {
                background-color: #3f3f46 !important;
            }

            body.bg-system .adaptive-modal-warning {
                background-color: #3f1a1a !important;
                border-color: #5c2626 !important;
            }
        }

        /* Smooth transitions */
        .adaptive-modal-header,
        .adaptive-modal-body,
        .adaptive-modal-footer,
        .adaptive-modal-highlight,
        .adaptive-modal-warning {
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .adaptive-modal-text {
            transition: color 0.3s ease;
        }
    </style>
</x-layouts::app>