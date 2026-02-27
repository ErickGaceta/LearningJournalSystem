@php
    $success = session('success') && !str_contains(session('success'), 'Temporary password') 
        ? session('success') 
        : null;

    $error = session('error') ?? ($errors->any() ? $errors->first() : null);
@endphp

@if($success || $error)
<div
    x-data="{ show: false }"
    x-init="$nextTick(() => { show = true; setTimeout(() => show = false, 5000) })"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:leave="transition ease-in duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-lg"
    style="position: fixed; top: 5px; right: 5px;"
>

<flux:callout
    :icon="$error ? 'x-circle' : 'check-circle'"
    :variant="$error ? 'danger' : 'success'"
>

    <div class="flex justify-between items-start gap-3">

        <div class="flex-1">

            <flux:callout.heading>
                {{ $error ? 'Action Failed' : 'Success' }}
            </flux:callout.heading>

            <flux:callout.text>
                {{ $error ?? $success }}
            </flux:callout.text>

        </div>

        <flux:button
            size="xs"
            variant="ghost"
            @click="show = false"
        >
            <flux:icon.x-mark class="size-4"/>
        </flux:button>

    </div>

</flux:callout>

</div>
@endif