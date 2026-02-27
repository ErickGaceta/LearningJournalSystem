<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
    <x-flash-banner />
    <x-temp-password-banner />
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>