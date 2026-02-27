<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <flux:sidebar
            sticky
            collapsible="mobile"
            class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">

            <flux:sidebar.header>
                <x-app-logo :sidebar="true" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:separator class="my-4" />

                {{-- ========== ADMIN SIDEBAR ========== --}}
                @if(auth()->user()->user_type === 'admin')
                <flux:sidebar.group>
                    <flux:sidebar.item
                        icon="home"
                        :href="route('admin.dashboard')"
                        :current="request()->routeIs('admin.dashboard')"
                        wire:navigate
                        class="font-semibold">
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>

                    <flux:separator class="my-4" />

                    <flux:sidebar.item
                        icon="users"
                        :href="route('admin.users.index')"
                        :current="request()->routeIs('admin.users.*')"
                        wire:navigate
                        class="font-semibold">
                        {{ __('Users') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="briefcase"
                        :href="route('admin.positions.index')"
                        :current="request()->routeIs('admin.positions.*')"
                        wire:navigate
                        class="font-semibold">
                        {{ __('Positions') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="building-office"
                        :href="route('admin.divisions.index')"
                        :current="request()->routeIs('admin.divisions.*')"
                        wire:navigate
                        class="font-semibold">
                        {{ __('Divisions/ Units') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                @endif

                {{-- ========== HR SIDEBAR ========== --}}
                @if(auth()->user()->user_type === 'hr')
                <flux:sidebar.group>
                    <flux:sidebar.item
                        icon="home"
                        :href="route('hr.dashboard')"
                        :current="request()->routeIs('hr.dashboard')"
                        wire:navigate
                        class="font-semibold">
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>

                    <flux:separator class="my-4" />

                    <flux:sidebar.item
                        icon="academic-cap"
                        :href="route('hr.modules.index')"
                        :current="request()->routeIs('hr.modules.*')"
                        wire:navigate
                        class="font-semibold">
                        {{ __('Training & Assignments') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="academic-cap"
                        :href="route('hr.monitoring.index')"
                        :current="request()->routeIs('hr.monitoring.*')"
                        wire:navigate
                        class="font-semibold">
                        {{ __('Monitoring') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                @endif

                {{-- ========== USER SIDEBAR ========== --}}
                @if(auth()->user()->user_type === 'user')
                <flux:sidebar.group class="grid">
                    <flux:sidebar.item
                        icon="home"
                        :href="route('user.dashboard')"
                        :current="request()->routeIs('user.dashboard')"
                        wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>

                    <flux:separator class="my-4" />

                    <flux:sidebar.item
                        icon="academic-cap"
                        :href="route('user.trainings.index')"
                        :current="request()->routeIs('user.trainings.*')"
                        wire:navigate>
                        {{ __('My Trainings') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="document-text"
                        :href="route('user.documents.index')"
                        :current="request()->routeIs('user.documents.*')"
                        wire:navigate>
                        {{ __('Learning Journals') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
                @endif

                <flux:separator class="my-4" />
            </flux:sidebar.nav>

            <flux:spacer />

            <div class="hidden lg:block px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="w-full">
                    <flux:radio value="light" icon="sun" />
                    <flux:radio value="dark" icon="moon" />
                    <flux:radio value="system" icon="computer-desktop" />
                </flux:radio.group>
            </div>

            <x-desktop-user-menu
                class="hidden lg:block"
                :name="auth()->user()->full_name" />
        </flux:sidebar>


        {{-- CONTENT WRAPPER --}}
        <div class="flex-1 flex flex-col">

            {{-- MOBILE HEADER --}}
            <flux:header sticky container class="lg:hidden border-b bg-zinc-200 dark:bg-zinc-700 border-zinc-200 dark:border-zinc-700">
                <flux:sidebar.toggle icon="bars-2" inset="left" />
                <flux:heading size="lg" class="ml-3">
                    {{ config('app.name') }}
                </flux:heading>
                <flux:spacer />
                {{-- dropdown unchanged --}}
            </flux:header>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 p-4 lg:p-8 overflow-auto">
                {{ $slot }}
            </main>

        </div>

    </div>

    @fluxScripts
    @livewireScripts
</body>



</html>