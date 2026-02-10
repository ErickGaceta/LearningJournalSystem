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
        
        <div class="flex flex-col w-full gap-2">
            <div class="flex justify-end">
                <flux:button :href="route('hr.assignments.create')" size="sm" icon="folder-plus" variant="primary" color="teal">Create New Training</flux:button>
            </div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Employee Name</flux:table.column>
                    <flux:table.column>Training Hours</flux:table.column>
                    <flux:table.column>Assigned Module</flux:table.column>
                    <flux:table.column>Venue</flux:table.column>
                    <flux:table.column>Duration</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($assignments as $as)
                    <flux:table.row>
                        <flux:table.cell>{{ $as->employee_name }}</flux:table.cell>
                        <flux:table.cell>{{ $as->module->hours }}</flux:table.cell>
                        <flux:table.cell>{{ $as->module->title}}</flux:table.cell>
                        <flux:table.cell>{{ $as->module->venue }}</flux:table.cell>
                        <flux:table.cell>{{ $as->module->hours }} hours</flux:table.cell>
                    </flux:table.row>
                    @empty
                    <flux:table.row>
                        <flux:table.cell class="col-span-7" align="center">No Assigned Training Yet</flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>