<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 pb-16 lg:pb-0">
    {{-- Desktop Sidebar (hidden on mobile) --}}
    <flux:sidebar sticky class="hidden lg:flex border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header class="cursor-default">
            <x-app-logo class="cursor-default" :sidebar="true" wire:navigate />
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
        <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="w-full">
                <flux:radio value="light" icon="sun" />
                <flux:radio value="dark" icon="moon" />
                <flux:radio value="system" icon="computer-desktop" />
            </flux:radio.group>
        </div>

        <x-desktop-user-menu :name="auth()->user()->full_name" />
    </flux:sidebar>

    {{ $slot }}

    {{-- Mobile Bottom Navigation --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-zinc-900 pb-16 lg:pb-0">
        {{-- ========== ADMIN BOTTOM NAV ========== --}}
        @if(auth()->user()->user_type === 'admin')
        <div class="flex items-center justify-around h-16 px-2">
            <a href="{{ route('admin.dashboard') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <flux:button
                    type="Dashboard"
                    variant="ghost"
                    icon="home" />
            </a>

            <a href="{{ route('admin.users.index') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <flux:button
                    type="users"
                    variant="ghost"
                    icon="Users" />
            </a>

            <a href="{{ route('admin.positions.index') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.positions.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <flux:button
                    type="Positions"
                    variant="ghost"
                    icon="briefcase" />
            </a>

            <a href="{{ route('admin.divisions.index') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.divisions.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <flux:button
                    type="Divitions"
                    variant="ghost"
                    icon="building-office" />
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button
                    type="submit"
                    variant="ghost"
                    icon="user-circle" />
            </form>
        </div>
        @endif

        {{-- ========== HR BOTTOM NAV ========== --}}
        @if(auth()->user()->user_type === 'hr')
        <div class="flex items-center justify-around h-16 px-2">
            <a href="{{ route('hr.dashboard') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('hr.dashboard') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <<flux:button
                    type="Dashboard"
                    variant="ghost"
                    icon="home" />
            </a>

            <a href="{{ route('hr.modules.index') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('hr.modules.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">

                <flux:button
                    type="Modules"
                    variant="ghost"
                    icon="academic-cap" />
            </a>

            <a href="{{ route('hr.assignments.index') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('hr.assignments.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <flux:button
                    type="Assignments"
                    variant="ghost"
                    icon="clipboard-document-list" />
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button
                    type="submit"
                    variant="ghost"
                    icon="user-circle" />
            </form>
        </div>
        @endif

        {{-- ========== USER BOTTOM NAV ========== --}}
        @if(auth()->user()->user_type === 'user')
        <div class="flex items-center justify-around h-16 px-2">
            <a href="{{ route('user.dashboard') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('user.dashboard') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <flux:button
                    type="Dashboard"
                    variant="ghost"
                    icon="home" />
            </a>

            <a href="{{ route('user.trainings.index') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('user.trainings.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <flux:button
                    type="Trainings"
                    variant="ghost"
                    icon="academic-cap" />
            </a>

            <a href="{{ route('user.documents.index') }}"
                wire:navigate
                class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('user.documents.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950' : 'text-zinc-600 dark:text-zinc-400' }}">
                <flux:button
                    type="Journals"
                    variant="ghost"
                    icon="doucment-text" />
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button
                    type="submit"
                    variant="ghost"
                    icon="user-circle" />
            </form>
        </div>
        @endif
    </nav>

    @fluxScripts
    @livewireScripts
</body>

</html>
