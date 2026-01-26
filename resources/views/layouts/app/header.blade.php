<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

        <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Home') }}
            </flux:navbar.item>

            <flux:dropdown>
                <flux:navbar.item icon="document-text" icon-trailing="chevron-down">
                    {{ __('Training Reports') }}
                </flux:navbar.item>

                <flux:menu>
                    @php
                    $documents = \App\Models\Document::where('user_id', auth()->id())
                    ->latest()
                    ->take(10)
                    ->get();
                    @endphp

                    @forelse($documents as $document)
                    <flux:menu.item :href="route('documents.show', $document)" wire:navigate>
                        <div class="flex flex-col">
                            <span class="font-medium">{{ Str::limit($document->title, 40) }}</span>
                            <span class="text-xs text-gray-500">{{ $document->date->format('M d, Y') }}</span>
                        </div>
                    </flux:menu.item>
                    @empty
                    <flux:menu.item disabled>
                        {{ __('No Recent Reports') }}
                    </flux:menu.item>
                    @endforelse

                    <flux:menu.separator />

                    <flux:menu.item :href="route('documents.create')" wire:navigate icon="plus">
                        {{ __('Create New Report') }}
                    </flux:menu.item>

                    <flux:menu.item :href="route('documents.index')" wire:navigate icon="document-duplicate">
                        {{ __('View All Reports') }}
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
            <flux:tooltip :content="__('Search')" position="bottom">
                <flux:navbar.item class="h-10! [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
            </flux:tooltip>
            <!-- <flux:tooltip :content="__('Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        :label="__('Repository')"
                    />
                </flux:tooltip> -->
            <!-- <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits#livewire"
                        target="_blank"
                        label="Documentation"
                    />
                </flux:tooltip> -->
        </flux:navbar>

        <x-desktop-user-menu />
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')">
                <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard')  }}
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>
    </flux:sidebar>

    {{ $slot }}

    @fluxScripts
</body>

</html>