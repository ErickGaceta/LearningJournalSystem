<div class="px-3 py-2 space-y-1 text-xs text-zinc-500 dark:text-zinc-400">
    <div class="flex justify-between gap-4">
        <span class="shrink-0">Built by</span>
        <div class="font-medium text-zinc-700 dark:text-zinc-300 text-right">
            <flux:heading size="lg" accent="true">BCU Interns</flux:heading>
            <div class="flex flex-col">
                <flux:link href="https://www.facebook.com/erick.gaceta.3">Erick Gaceta</flux:link>
                <flux:link href="https://www.facebook.com/keita.pendragon.9">Ryan Joseph Fagyan</flux:link>
                <flux:link href="https://www.facebook.com/dcabz27">Derrik Cabanilla</flux:link>
            </div>
        </div>
    </div>
    <div class="flex justify-between">
        <span>Stack</span>
        <span class="font-medium text-zinc-700 dark:text-zinc-300">Laravel 12 · Flux · Alpine</span>
    </div>
    <div class="flex justify-between">
        <span>PHP</span>
        <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ PHP_VERSION }}</span>
    </div>
    <div class="flex justify-between">
        <span>Laravel</span>
        <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ app()->version() }}</span>
    </div>
    <div class="flex justify-between">
        <span>Environment</span>
        <span class="font-medium {{ app()->environment('production') ? 'text-green-600' : 'text-amber-500' }}">
            {{ ucfirst(app()->environment()) }}
        </span>
    </div>
</div>