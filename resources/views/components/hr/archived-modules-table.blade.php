@props(['modules'])

<div class="space-y-6">

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-2">
            <flux:icon.archive-box class="w-5 h-5 text-amber-500" />
            <flux:text size="sm" variant="subtle">
                {{ $modules->total() }} archived {{ Str::plural('module', $modules->total()) }}
            </flux:text>
        </div>
        <div class="flex items-center gap-2">
            <flux:button
                href="{{ route('hr.modules.index') }}"
                variant="ghost"
                size="sm"
                icon="arrow-left">
                Back to Active Modules
            </flux:button>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4"
        x-data="{ search: '' }">

        <flux:input
            x-model="search"
            placeholder="Search archived modules..."
            icon="magnifying-glass"
            clearable
            class="max-w-sm" />

        {{-- Empty State --}}
        @if($modules->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-neutral-100 dark:bg-neutral-700 mb-4">
                <flux:icon.archive-box class="w-8 h-8 text-neutral-400" />
            </div>
            <flux:heading size="lg" class="text-neutral-500">No Archived Modules</flux:heading>
            <flux:text size="sm" variant="subtle" class="mt-1 max-w-xs">
                Modules you archive will appear here. You can restore them at any time.
            </flux:text>
            <flux:button
                href="{{ route('hr.modules.index') }}"
                variant="ghost"
                size="sm"
                icon="arrow-left"
                class="mt-4">
                Back to Active Modules
            </flux:button>
        </div>
        @else
        {{-- Modules Table --}}
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-700">
                        <th class="text-left py-3 px-4 font-medium text-neutral-500 dark:text-neutral-400">Module</th>
                        <th class="text-left py-3 px-4 font-medium text-neutral-500 dark:text-neutral-400">Assignments</th>
                        <th class="text-left py-3 px-4 font-medium text-neutral-500 dark:text-neutral-400">Archived On</th>
                        <th class="text-right py-3 px-4 font-medium text-neutral-500 dark:text-neutral-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                    @foreach($modules as $module)
                    <tr
                        class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors"
                        x-show="search === '' || '{{ strtolower($module->title) }}'.includes(search.toLowerCase())">
                        {{-- Module Info --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/30 shrink-0">
                                    <flux:icon.archive-box class="w-4 h-4 text-amber-500" />
                                </div>
                                <div>
                                    <p class="font-medium text-neutral-800 dark:text-neutral-100">
                                        {{ $module->title }}
                                    </p>
                                    @if($module->description)
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5 line-clamp-1">
                                        {{ $module->description }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Assignments Count --}}
                        <td class="py-4 px-4">
                            <flux:badge color="zinc" size="sm">
                                {{ $module->assignments_count ?? 0 }}
                                {{ Str::plural('employee', $module->assignments_count ?? 0) }}
                            </flux:badge>
                        </td>

                        {{-- Archived Date --}}
                        <td class="py-4 px-4">
                            <flux:text size="sm" variant="subtle">
                                {{ $module->archived_at?->format('M d, Y') ?? $module->updated_at->format('M d, Y') }}
                            </flux:text>
                        </td>

                        {{-- Actions --}}
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">

                                {{-- Restore Button --}}
                                <flux:modal.trigger name="restore-module-{{ $module->id }}">
                                    <flux:button variant="ghost" size="sm" icon="arrow-path">
                                        Restore
                                    </flux:button>
                                </flux:modal.trigger>

                                {{-- Delete Button --}}
                                <flux:modal.trigger name="delete-archived-module-{{ $module->id }}">
                                    <flux:button variant="ghost" size="sm" icon="trash" color="red">
                                        Delete
                                    </flux:button>
                                </flux:modal.trigger>

                            </div>
                        </td>
                    </tr>

                    {{-- Restore Modal --}}
                    <flux:modal name="restore-module-{{ $module->id }}" class="max-w-md">
                        <form action="{{ route('hr.modules.restore', $module) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="p-2 bg-white dark:bg-neutral-800">
                                <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                                    <flux:icon.arrow-path class="w-8 h-8 text-teal-500" />
                                </div>
                            </div>

                            <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
                                <flux:heading size="lg" class="text-center">Restore Training Module?</flux:heading>

                                <div class="rounded-lg p-4 shadow-sm text-center">
                                    <flux:text size="sm">You are about to restore:</flux:text>
                                    <flux:text size="lg" class="font-semibold mt-2">{{ $module->title }}</flux:text>
                                </div>

                                <div class="bg-teal-50 dark:bg-teal-950/30 rounded-lg p-4">
                                    <div class="flex flex-col items-center gap-2">
                                        <flux:icon.information-circle class="w-5 h-5 text-teal-500 dark:text-teal-400" />
                                        <flux:text size="sm" class="text-center">
                                            <strong class="text-teal-500">Note:</strong> This module will become active again and visible for assignment.
                                        </flux:text>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
                                <flux:modal.close>
                                    <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
                                </flux:modal.close>
                                <flux:button type="submit" variant="primary" color="teal" size="sm" icon="arrow-path" class="flex-1">
                                    Restore Module
                                </flux:button>
                            </div>
                        </form>
                    </flux:modal>

                    {{-- Permanent Delete Modal --}}
                    <flux:modal name="delete-archived-module-{{ $module->id }}" class="max-w-md">
                        <form action="{{ route('hr.modules.destroy', $module) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="p-2 bg-white dark:bg-neutral-800">
                                <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                                    <flux:icon.trash class="w-8 h-8 text-red-500" />
                                </div>
                            </div>

                            <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
                                <flux:heading size="lg" class="text-center">Delete Permanently?</flux:heading>

                                <div class="rounded-lg p-4 shadow-sm text-center">
                                    <flux:text size="sm">You are about to permanently delete:</flux:text>
                                    <flux:text size="lg" class="font-semibold mt-2">{{ $module->title }}</flux:text>
                                </div>

                                <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4">
                                    <div class="flex flex-col items-center gap-2">
                                        <flux:icon.exclamation-triangle class="w-5 h-5 text-red-500 dark:text-red-400" />
                                        <flux:text size="sm" class="text-center">
                                            <strong class="text-red-500">Warning:</strong> This cannot be undone. All associated assignments will also be permanently deleted.
                                        </flux:text>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
                                <flux:modal.close>
                                    <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
                                </flux:modal.close>
                                <flux:button type="submit" variant="primary" color="red" size="sm" icon="trash" class="flex-1">
                                    Delete Permanently
                                </flux:button>
                            </div>
                        </form>
                    </flux:modal>

                    @endforeach
                </tbody>
            </table>
        </div>

            
            @if($modules->hasPages())
            <div class="mt-4 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                {{ $modules->links() }}
            </div>
            @endif
        @endif
    </div>
</div>