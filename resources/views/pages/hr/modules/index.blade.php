<x-layouts::app :title="__('Training Modules')">
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

        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Module Title</flux:table.column>
                    <flux:table.column>Training Hours</flux:table.column>
                    <flux:table.column>Start - End</flux:table.column>
                    <flux:table.column>Venue</flux:table.column>
                    <flux:table.column>Sponsor/ Conductor</flux:table.column>
                    <flux:table.column>Registration Fee</flux:table.column>
                    <flux:table.column>Travel Expenses</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    <flux:table.row>
                        <flux:table.cell></flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                    </flux:table.row>
        </div>
    </div>
</x-layouts::app>