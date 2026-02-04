<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header class="cursor-default">
            <x-app-logo class="cursor-default" :sidebar="true" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden cursor-default" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Learning Journal')" class="grid">
                {{-- User-only link --}}
                @if(auth()->user()->user_type === 'user')
                <flux:sidebar.item
                    icon="home"
                    :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
                @endif

                {{-- Admin-only Documents Dropdown --}}
                @if(auth()->user()->user_type === 'admin')
                <flux:sidebar icon="document-text">
                    {{ __('Documents') }}

                    <flux:sidebar.group>
                        {{-- View All Documents --}}
                        <flux:sidebar.item
                            icon="folder-open"
                            :href="route('documents.index')"
                            :current="request()->routeIs('documents.index')"
                            wire:navigate
                            class="font-semibold">
                            {{ __('View All Documents') }}
                        </flux:sidebar.item>

                        <flux:sidebar.item icon="user-circle" :href="route('positions.index')" :current="request()->routeIs('positions.index')" wire:navigate class="font-semibold">
                            {{ __('Positions') }}
                        </flux:sidebar>

                        <flux:sidebar.item icon="building-office" :href="route('divisions.index')" :current="request()->routeIs('divisions.index')" wire:navigate class="font-semibold">
                            {{ __('Divisions') }}
                        </flux:sidebar>
                    </flux:sidebar.group>
                </flux:sidebar>
                @endif
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        <!-- Appearance Switcher (Desktop) -->
        <div class="hidden lg:block px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="w-full">
                <flux:radio value="light" icon="sun" />
                <flux:radio value="dark" icon="moon" />
                <flux:radio value="system" icon="computer-desktop" />
            </flux:radio.group>
        </div>

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->full_name" />
    </flux:sidebar>

    {{ $slot }}

    @fluxScripts
</body>

</html>