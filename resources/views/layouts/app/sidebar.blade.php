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
                    {{ __('Divisions') }}
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
                    {{ __('Training Modules') }}
                </flux:sidebar.item>

                <flux:sidebar.item 
                    icon="clipboard-document-list"
                    :href="route('hr.assignments.index')" 
                    :current="request()->routeIs('hr.assignments.*')" 
                    wire:navigate 
                    class="font-semibold">
                    {{ __('Assignments') }}
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