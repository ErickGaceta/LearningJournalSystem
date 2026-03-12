@props(['module', 'users'])

@php
$alreadyAssigned = $module->assignments->pluck('user_id')->toArray();
@endphp

{{-- Assign Modal --}}
<flux:modal name="assign-module-{{ $module->id }}" class="max-w-md">
    <form action="{{ route('hr.assignments.store') }}" method="POST" class="flex flex-col gap-0">
        @csrf
        <input type="hidden" name="module_id" value="{{ $module->id }}">

        <div class="p-6 bg-white dark:bg-neutral-800 space-y-4">
            <div>
                <flux:heading size="lg">Assign Employees</flux:heading>
                <flux:text variant="subtle" class="text-sm mt-1">{{ $module->title }}</flux:text>
            </div>

            <flux:separator />

            <div x-data="{ search: '' }">
                <flux:input
                    x-model="search"
                    placeholder="Search employees..."
                    icon="magnifying-glass"
                    clearable
                    class="mb-3" />

                <flux:fieldset>
                    <flux:legend>Select Employees</flux:legend>
                    <div class="max-h-64 overflow-y-auto flex flex-col gap-1 pr-1">
                        <flux:checkbox.group>
                            <flux:checkbox.all label="Select all" />
                            @foreach($users as $user)
                            @php $isAssigned = in_array($user->id, $alreadyAssigned); @endphp
                            <div x-show="search === '' || '{{ strtolower($user->full_name) }}'.includes(search.toLowerCase())">
                                <flux:checkbox
                                    name="user_ids[]"
                                    value="{{ $user->id }}"
                                    :label="$user->full_name . ($isAssigned ? ' ✓' : '')"
                                    :checked="$isAssigned"
                                    :disabled="$isAssigned" />
                            </div>
                            @endforeach
                        </flux:checkbox.group>
                    </div>
                </flux:fieldset>

                <p x-show="search !== '' && document.querySelectorAll('[name=\'user_ids[]\']:not([style*=\'display: none\'])').length === 0"
                    class="text-sm text-neutral-500 text-center py-2">
                    No employees found.
                </p>
            </div>

            @if($module->assignments_count > 0)
            <flux:text size="sm" variant="subtle">
                {{ $module->assignments_count }} {{ Str::plural('employee', $module->assignments_count) }} already assigned.
            </flux:text>
            @endif
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" color="teal" size="sm" icon="user-plus" class="flex-1">
                Assign
            </flux:button>
        </div>
    </form>
</flux:modal>

{{-- Archive Modal --}}
<flux:modal name="archive-module-{{ $module->id }}" class="max-w-md">
    <form action="{{ route('hr.modules.archive', $module) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="p-2 bg-white dark:bg-neutral-800">
            <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                <flux:icon.archive-box class="w-8 h-8 text-amber-500" />
            </div>
        </div>

        <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
            <flux:heading size="lg" class="text-center">Archive Training Module?</flux:heading>

            <div class="rounded-lg p-4 shadow-sm text-center">
                <flux:text size="sm">You are about to archive:</flux:text>
                <flux:text size="lg" class="font-semibold mt-2">{{ $module->title }}</flux:text>
            </div>

            <div class="bg-amber-50 dark:bg-amber-950/30 rounded-lg p-4">
                <div class="flex flex-col items-center gap-2">
                    <flux:icon.information-circle class="w-5 h-5 text-amber-500 dark:text-amber-400" />
                    <flux:text size="sm" class="text-center">
                        <strong class="text-amber-500">Note:</strong> This module will be hidden from active use. Existing assignments will be preserved and it can be restored later.
                    </flux:text>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" color="amber" size="sm" icon="archive-box" class="flex-1">
                Archive Module
            </flux:button>
        </div>
    </form>
</flux:modal>

{{-- Delete Modal --}}
<flux:modal name="delete-module-{{ $module->id }}" class="max-w-md">
    <form action="{{ route('hr.modules.archive', $module) }}" method="POST">
        @csrf
        @method('PATCH')

<div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
        <flux:heading size="lg" class="text-center">Manage User</flux:heading>

        <div class="rounded-lg p-4 shadow-sm text-center">
            <flux:text size="sm">You are managing:</flux:text>
            <flux:text size="lg" class="font-semibold mt-2"
                x-text="selectedUser
                    ? [selectedUser.first_name, selectedUser.middle_name, selectedUser.last_name].filter(Boolean).join(' ')
                    : ''">
            </flux:text>
            <flux:text size="sm" variant="subtle" x-text="selectedUser?.email"></flux:text>
        </div>

        <!-- Archive -->

<div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4">
            <div class="flex flex-col items-center gap-2">
                <flux:icon.archive-box class="w-5 h-5 text-red-500 dark:text-red-400" />
                <flux:text size="sm" class="text-center">
                    <strong class="text-red-500">Archive:</strong>
                    This user will no longer be able to log in. This action can be undone at the Archive Tab.
                </flux:text>
                <form
                    :action="`{{ url('hr/module/index') }}/${selectedUser?.id}/archive`"
                    method="POST"
                    class="w-full"
                >
                    @csrf
                    @method('PATCH')
                    <flux:button type="submit" variant="filled" color="red" size="sm" class="w-full">
                        Archive Training
                    </flux:button>
                </form>
            </div>
        </div>

 <!-- Delete -->
    <form action="{{ route('hr.modules.destroy', $module) }}" method="POST">
        @csrf
        @method('DELETE')



            <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4">
                <div class="flex flex-col items-center gap-2">
                    <flux:icon.information-circle class="w-5 h-5 text-red-500 dark:text-red-400" />
                    <flux:text size="sm" class="text-center">
                        <strong class="text-red-500">Warning:</strong> This cannot be undone. All associated assignments will also be deleted.
                    </flux:text>
                     <form
                    :action="`{{ url('hr/module/index') }}/${selectedUser?.id}/archive`"
                    method="POST"
                    class="w-full"
                >
                    @csrf
                    @method('PATCH')
                    <flux:button type="submit" variant="filled" color="red" size="sm" class="w-full">
                         Delete Permanently
                    </flux:button>
                </form>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
            </flux:modal.close>
        </div>
    </form>
</flux:modal>

