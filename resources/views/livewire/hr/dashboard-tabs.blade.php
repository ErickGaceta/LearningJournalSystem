<div class="flex flex-col gap-4">

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
    @if ($activeTab === 'dashboard')
    <x-hr.dashboard.wrapper
        :modules="$modules"
        :totalModules="$totalModules"
        :activeTraining="$activeTraining"
        :usersInTraining="$usersInTraining" />
    @endif

    @if ($activeTab === 'monitoring')
    <livewire:hr.monitoring-index lazy />
    <x-pdf-preview-modal :url="url('hr/monitoring/documents')" event="open-document-preview" />
    @endif

</div>