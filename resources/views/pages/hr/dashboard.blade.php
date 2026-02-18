<x-layouts::app :title="__('HR Dashboard')">
    <div class="flex flex-col h-full w-full gap-4 items-center justify-start pt-12">
        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm w-72">
            <div class="flex items-center">
                <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif
        <div class="flex flex-col gap-4 items-center">
            <flux:card class="w-72 h-28 flex flex-col justify-center text-center">
                <flux:heading size="xl">Total Modules/ Trainings</flux:heading>
                <flux:text class="mt-2">{{ $totalModules }}</flux:text>
            </flux:card>
            <flux:card class="w-72 h-28 flex flex-col justify-center text-center">
                <flux:heading size="xl">Active Trainings</flux:heading>
                <flux:text class="mt-2">{{ $activeTraining }}</flux:text>
            </flux:card>
            <flux:card class="w-72 h-28 flex flex-col justify-center text-center">
                <flux:heading size="xl">Users in Training</flux:heading>
                <flux:text class="mt-2">{{ $usersInTraining }}</flux:text>
            </flux:card>
        </div>
    </div>
</x-layouts::app>