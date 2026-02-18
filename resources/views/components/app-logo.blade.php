@props([
    'sidebar' => false,
])

@php
    $href = '/';
    if (auth()->check()) {
        $href = match(auth()->user()->user_type) {
            'admin' => route('admin.dashboard'),
            'hr'    => route('hr.dashboard'),
            default => route('user.dashboard'),
        };
    }
@endphp

@if($sidebar)
    <flux:sidebar.brand name="DOST-CAR LJS" :href="$href" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md text-accent-foreground">
            <x-app-logo-icon class="size-5" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="DOST-CAR LJS" :href="$href" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md text-accent-foreground">
            <x-app-logo-icon class="size-5" />
        </x-slot>
    </flux:brand>
@endif