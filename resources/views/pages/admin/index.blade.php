<x-layouts::app :title="__('Admin Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm">
            <div class="flex items-center">
                <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card>
                <flux:heading size="lg">Total Journals</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->count() }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Total Hours</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->sum('hours') }}
                </flux:text>
            </flux:card>
            <flux:card>
                <flux:heading size="lg">Total Journals This Year</flux:heading>
                <flux:text class="mt-2 mb-4">
                    {{ \App\Models\Document::where('user_id', auth()->id())->whereYear('datestart', date('Y'))->count() }}
                </flux:text>
            </flux:card>
        </div>
    </div>
</x-layouts::app>