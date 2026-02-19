@props([
    'model'   => null,
    'mode'    => 'create',   // create | edit | show | delete
    'name'    => '',         // base name e.g. 'division', 'position', 'module'

    // Create / Edit
    'title'   => '',
    'subtitle' => '',
    'action'  => '',         // form action URL
    'fields'  => [],         // [['label' => '', 'name' => '', 'placeholder' => '', 'value' => '']]

    // Show
    'details' => [],         // [['label' => '', 'value' => '']]

    // Delete
    'deleteLabel' => '',     // what's shown as the item being deleted
])

@php
    $modalName = match($mode) {
        'create' => "{$name}-create",
        default  => "{$name}-{$mode}-{$model?->id}",
    };

    $method = match($mode) {
        'create' => 'POST',
        'edit'   => 'PUT',
        'delete' => 'DELETE',
        default  => null,
    };
@endphp

<flux:modal :name="$modalName" class="{{ $mode === 'show' ? 'max-w-2xl' : 'max-w-md' }}">

    {{-- CREATE / EDIT --}}
    @if(in_array($mode, ['create', 'edit']))
        <form action="{{ $action }}" method="POST">
            @csrf
            @if($method !== 'POST') @method($method) @endif

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
                    <flux:input type="text" name="{{ $field['name'] }}" size="sm" value="{{ old($field['name'], $field['value'] ?? '') }}" placeholder="{{ $field['placeholder'] ?? '' }}" required />
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
                <flux:button
                    type="submit"
                    variant="primary"
                    size="sm"
                    color="{{ $mode === 'create' ? 'green' : 'sky' }}"
                    icon="{{ $mode === 'create' ? 'folder-arrow-down' : 'arrow-up-on-square' }}">
                    {{ $mode === 'create' ? 'Create' : 'Update' }}
                </flux:button>
            </div>
        </form>

    {{-- SHOW --}}
    @elseif($mode === 'show')
        <div class="p-6 space-y-6 bg-white dark:bg-neutral-800">
            <div class="flex justify-between items-start">
                <div>
                    <flux:heading size="lg" class="text-zinc-900 dark:text-white">{{ $title }}</flux:heading>
                    @if($subtitle)
                        <flux:text size="sm" class="text-neutral-600">{{ $subtitle }}</flux:text>
                    @endif
                </div>
                <flux:modal.trigger name="{{ $name }}-edit-{{ $model?->id }}">
                    <flux:button variant="primary" color="sky" size="sm" icon="pencil">Edit</flux:button>
                </flux:modal.trigger>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                <dl class="grid grid-cols-1 gap-3">
                    @foreach($details as $detail)
                    <div>
                        <dt class="text-xs font-medium text-neutral-500 uppercase">{{ $detail['label'] }}</dt>
                        <dd class="mt-1 text-sm text-heading">{{ $detail['value'] }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>

            {{-- Extra content slot (e.g. related users table) --}}
            {{ $slot }}
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex justify-end">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" icon="arrow-uturn-left">Close</flux:button>
            </flux:modal.close>
        </div>

    {{-- DELETE --}}
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
                    {{ $title ?: 'Delete?' }}
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