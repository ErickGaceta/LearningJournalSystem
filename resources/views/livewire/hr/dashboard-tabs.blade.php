<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

    {{-- Tab Bar --}}
    <div class="flex gap-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl p-1 w-fit">
        <button
            wire:click="$set('activeTab', 'dashboard')"
            class="px-4 py-1.5 rounded-lg text-sm transition-colors duration-150 cursor-pointer
                {{ $activeTab === 'dashboard'
                    ? 'bg-white dark:bg-zinc-700 font-medium text-zinc-900 dark:text-white shadow-sm'
                    : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
            Dashboard
        </button>
        <button
            wire:click="$set('activeTab', 'monitoring')"
            class="px-4 py-1.5 rounded-lg text-sm transition-colors duration-150 cursor-pointer
                {{ $activeTab === 'monitoring'
                    ? 'bg-white dark:bg-zinc-700 font-medium text-zinc-900 dark:text-white shadow-sm'
                    : 'text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200' }}">
            Monitoring
        </button>
    </div>

    {{-- Tab Panels --}}
    {{-- Tab Panels --}}
    @if ($activeTab === 'dashboard')

    {{-- Loading skeleton while tab switches --}}
    <div wire:loading.block wire:target="$set">
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl animate-pulse">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="h-24 bg-zinc-100 dark:bg-zinc-800 rounded-xl"></div>
                <div class="h-24 bg-zinc-100 dark:bg-zinc-800 rounded-xl"></div>
                <div class="h-24 bg-zinc-100 dark:bg-zinc-800 rounded-xl"></div>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="grid grid-cols-4 gap-4 px-4 py-3 bg-zinc-50 dark:bg-zinc-800">
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                </div>
                @foreach(range(1, 5) as $i)
                <div class="grid grid-cols-4 gap-4 px-4 py-4 border-t border-zinc-100 dark:border-zinc-700">
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-3/4"></div>
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-1/2"></div>
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-2/3 mx-auto"></div>
                    <div class="h-5 bg-zinc-100 dark:bg-zinc-800 rounded-full w-16 ml-auto"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Actual content --}}
    <div wire:loading.remove wire:target="$set">
        <x-hr.dashboard.wrapper
            :modules="$modules"
            :totalModules="$totalModules"
            :activeTraining="$activeTraining"
            :usersInTraining="$usersInTraining" />
    </div>

    @endif

    @if ($activeTab === 'monitoring')

    <div wire:loading.block wire:target="$set">
        <div class="flex flex-col gap-4 animate-pulse">
            <div class="flex gap-1 bg-zinc-100 dark:bg-zinc-800 rounded-xl p-1 w-fit">
                <div class="h-8 w-24 bg-white dark:bg-zinc-700 rounded-lg"></div>
                <div class="h-8 w-24 bg-zinc-200 dark:bg-zinc-600 rounded-lg opacity-50"></div>
                <div class="h-8 w-24 bg-zinc-200 dark:bg-zinc-600 rounded-lg opacity-50"></div>
                <div class="h-8 w-24 bg-zinc-200 dark:bg-zinc-600 rounded-lg opacity-50"></div>
            </div>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="grid grid-cols-5 gap-4 px-4 py-3 bg-zinc-50 dark:bg-zinc-800">
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded col-span-2"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded mx-auto w-2/3"></div>
                    <div class="h-3 bg-zinc-200 dark:bg-zinc-700 rounded ml-auto w-1/2"></div>
                </div>
                @foreach(range(1, 5) as $i)
                <div class="grid grid-cols-5 gap-4 px-4 py-4 border-t border-zinc-100 dark:border-zinc-700">
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded col-span-2 w-4/5"></div>
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-3/4"></div>
                    <div class="h-3 bg-zinc-100 dark:bg-zinc-800 rounded w-2/3 mx-auto"></div>
                    <div class="h-5 bg-zinc-100 dark:bg-zinc-800 rounded-full w-16 ml-auto"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div wire:loading.remove wire:target="$set">
        <livewire:hr.monitoring-index />
        <x-pdf-preview-modal :url="url('hr/monitoring/documents')" event="open-document-preview" />
    </div>

    @endif

</div>