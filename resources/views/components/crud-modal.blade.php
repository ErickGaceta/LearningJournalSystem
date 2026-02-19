@props([
'model' => null,
'mode' => 'create', // create | show | delete
'name' => '', // base name e.g. 'division', 'position'

// Shared
'title' => '',
'subtitle' => '',

// Create / Edit (used in both create mode and show mode's edit tab)
'action' => '',
'fields' => [], // [['label', 'name', 'placeholder', 'value', 'type', 'required']]

// Show (details tab)
'details' => [], // [['label', 'value']]

// Delete
'deleteLabel' => '',
])

@php
$modalName = match($mode) {
'create' => "{$name}-create",
default => "{$name}-{$mode}-{$model?->id}",
};
@endphp

<flux:modal :name="$modalName" class="{{ $mode === 'show' ? 'max-w-2xl' : 'max-w-md' }}">

    {{-- ═══════════════════════════════════════
         CREATE
    ════════════════════════════════════════ --}}
    @if($mode === 'create')
    <form action="{{ $action }}" method="POST">
        @csrf

        <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
            <div>
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">{{ $title }}</flux:heading>
                @if($subtitle)
                <flux:text size="sm" class="text-neutral-600">{{ $subtitle }}</flux:text>
                @endif
            </div>

            @foreach($fields as $field)
            <div>
                <flux:label class="pb-2">{{ $field['label'] }}</flux:label>
                <flux:input
                    type="{{ $field['type'] ?? 'text' }}"
                    name="{{ $field['name'] }}"
                    size="sm"
                    value="{{ old($field['name'], $field['value'] ?? '') }}"
                    placeholder="{{ $field['placeholder'] ?? '' }}"
                    {{ !empty($field['required']) ? 'required' : '' }} />
                @error($field['name'])
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endforeach

            {{ $slot }}
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" size="sm" color="green" icon="folder-arrow-down">
                Create
            </flux:button>
        </div>
    </form>

    {{-- ═══════════════════════════════════════
         SHOW + EDIT (tabbed)
    ════════════════════════════════════════ --}}
    @elseif($mode === 'show')
    <div x-data="{ tab: 'show' }" class="bg-white dark:bg-neutral-800">

        {{-- Header --}}
        <div class="px-6 pt-6 pb-0 space-y-3">
            <div>
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">{{ $title }}</flux:heading>
                @if($subtitle)
                <flux:text size="sm" class="text-neutral-600">{{ $subtitle }}</flux:text>
                @endif
            </div>

            {{-- Tabs --}}
            <div class="flex border-b border-neutral-200 dark:border-neutral-700">
                <button
                    type="button"
                    @click="tab = 'show'"
                    :class="tab === 'show'
                            ? 'border-b-2 border-teal-500 text-teal-600 dark:text-teal-400'
                            : 'text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'"
                    class="px-4 py-2 text-sm font-medium transition-colors">
                    Details
                </button>
                <button
                    type="button"
                    @click="tab = 'edit'"
                    :class="tab === 'edit'
                            ? 'border-b-2 border-teal-500 text-teal-600 dark:text-teal-400'
                            : 'text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300'"
                    class="px-4 py-2 text-sm font-medium transition-colors">
                    Edit
                </button>
            </div>
        </div>

        {{-- Details Tab --}}
        <div x-show="tab === 'show'" x-transition class="p-6 space-y-4">
            <dl class="grid grid-cols-1 gap-3">
                @foreach($details as $detail)
                <div>
                    <dt class="text-xs font-medium text-neutral-500 uppercase">{{ $detail['label'] }}</dt>
                    <dd class="mt-1 text-sm text-heading">{{ $detail['value'] }}</dd>
                </div>
                @endforeach
            </dl>

            {{-- Extra content (e.g. related users table) --}}
            {{ $slot }}

            <div class="flex justify-end gap-2 pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm" icon="arrow-uturn-left">Close</flux:button>
                </flux:modal.close>
                <flux:button
                    type="button"
                    variant="primary"
                    color="sky"
                    size="sm"
                    icon="pencil"
                    @click="tab = 'edit'">
                    Edit
                </flux:button>
            </div>
        </div>

        {{-- Edit Tab --}}
        <div x-show="tab === 'edit'" x-transition class="p-6 space-y-4">
            <form action="{{ $action }}" method="POST">
                @csrf
                @method('PUT')

                @foreach($fields as $field)
                <div class="mb-4">
                    <flux:label class="pb-2">{{ $field['label'] }}</flux:label>
                    <flux:input
                        type="text"
                        name="{{ $field['name'] }}"
                        size="sm"
                        value="{{ old($field['name'], $field['value'] ?? '') }}"
                        placeholder="{{ $field['placeholder'] ?? '' }}"
                        required />
                    @error($field['name'])
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endforeach

                <div class="flex justify-end gap-2 pt-2">
                    <flux:button type="button" variant="ghost" size="sm" icon="arrow-uturn-left" @click="tab = 'show'">
                        Back
                    </flux:button>
                    <flux:button type="submit" variant="primary" color="sky" size="sm" icon="arrow-up-on-square">
                        Update
                    </flux:button>
                </div>
            </form>
        </div>

    </div>

    {{-- ═══════════════════════════════════════
         DELETE
    ════════════════════════════════════════ --}}
    @elseif($mode === 'delete')
    <form action="{{ $action }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="p-6 bg-white dark:bg-neutral-800">
            <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                <flux:icon.exclamation-triangle class="w-8 h-8 text-red-500" />
            </div>
        </div>

        <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
            <flux:heading size="lg" class="text-center text-zinc-900 dark:text-white">
                {{ $title ?: 'Confirm Delete?' }}
            </flux:heading>

            <div class="rounded-lg p-4 shadow-sm text-center">
                <flux:text size="sm" class="text-zinc-900 dark:text-white">You are about to delete:</flux:text>
                <flux:text size="lg" class="font-semibold text-zinc-900 dark:text-white mt-2">
                    {{ $deleteLabel }}
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

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" color="red" size="sm" class="flex-1">
                Delete Permanently
            </flux:button>
        </div>
    </form>
    @endif

</flux:modal>