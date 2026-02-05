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
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="flex-1">{{ session('success') }}</span>
                <button @click="show = false" class="shrink-0 ml-4 text-white hover:text-green-100 transition-colors">
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
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="flex-1">{{ session('error') }}</span>
                <button @click="show = false" class="shrink-0 ml-4 text-white hover:text-red-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <div class="flex flex-col items-end justify-end gap-4 mt-2 me-2">
            <flux:button
                :href="route('admin.positions.create')"
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
                            <a href="{{ route('admin.positions.show', $position) }}"
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
                                :href="route('admin.positions.show', $position)"
                                size="sm"
                                icon="eye"
                                square />
                            <flux:button
                                :href="route('admin.positions.edit', $position)"
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
                            <flux:modal name="delete-position-{{ $position->id }}" class="max-w-md">
                                <form action="{{ route('admin.positions.destroy', $position) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <!-- Modal Header with Icon -->
                                    <div class="p-2 bg-white dark:bg-neutral-800">
                                        <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                                            <flux:icon.exclamation-triangle class="w-8 h-8 text-red-500 " />
                                        </div>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
                                        <flux:heading size="lg" class="text-center text-zinc-900 dark:text-white">
                                            Delete Division/Unit?
                                        </flux:heading>

                                        <div class="rounded-lg p-4 shadow-sm">
                                            <flux:text size="sm" class="text-zinc-900 dark:text-white text-center">
                                                You are about to delete:
                                            </flux:text>
                                            <flux:text size="lg" class="font-semibold text-zinc-900 dark:text-white text-center mt-2">
                                                {{ $position->positions }}
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

                                    <!-- Modal Footer -->
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
                                            Delete Permanently
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
                                <a href="{{ route('admin.positions.create') }}" class="text-blue-500 hover:underline">
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
        [x-cloak] {
            display: none !important;
        }

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