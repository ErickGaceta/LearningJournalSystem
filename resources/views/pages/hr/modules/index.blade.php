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
                <flux:button :href="route('hr.modules.create')" size="sm" icon="folder-plus" variant="primary" color="teal">Create New Training</flux:button>
                   <div class="flex gap-2">
            </div>
            </div>

            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Module Title</flux:table.column>
                    <flux:table.column>Training Hours</flux:table.column>
                    <flux:table.column>Start - End</flux:table.column>
                    <flux:table.column>Venue</flux:table.column>
                    <flux:table.column>Sponsor/ Conductor</flux:table.column>
                    <flux:table.column>Registration Fee</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($trainingModules as $tm)
                    <flux:table.row>
                        <flux:table.cell>{{ $tm->title }}</flux:table.cell>
                        <flux:table.cell>{{ $tm->hours }}</flux:table.cell>
                        <flux:table.cell>{{ $tm->datestart->format('Y-m-d') }} - {{ $tm->dateend->format('Y-m-d') }}</flux:table.cell>
                        <flux:table.cell>{{ $tm->venue }}</flux:table.cell>
                        <flux:table.cell>{{ $tm->conductedby }}</flux:table.cell>
                        <flux:table.cell>{{ $tm->registration_fee }}</flux:table.cell>

                        @foreach($trainingModules as $trainingModule)
<flux:table.cell align="right">
    <flux:button
        variant="ghost"
        color="emerald"
        :href="route('hr.modules.edit', $trainingModule)"
        size="sm"
        icon="eye"
        square />

    <!-- Delete Button with Modal -->
    <flux:modal.trigger name="delete-position-{{ $trainingModule->id }}">
        <flux:button
            variant="ghost"
            size="sm"
            icon="trash"
            square />
    </flux:modal.trigger>

    <!-- Delete Confirmation Modal using Flux -->
    <flux:modal name="delete-position-{{ $trainingModule->id }}" class="max-w-md">
        <form action="{{ route('hr.modules.destroy', $trainingModule) }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="p-2 bg-white dark:bg-neutral-800">
                <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                    <flux:icon.exclamation-triangle class="w-8 h-8 text-red-500" />
                </div>
            </div>

            <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
                <flux:heading size="lg" class="text-center text-zinc-900 dark:text-white">
                    Delete Training Module?
                </flux:heading>

                <div class="rounded-lg p-4 shadow-sm">
                    <flux:text size="sm" class="text-zinc-900 dark:text-white text-center">
                        You are about to delete:
                    </flux:text>
                    <flux:text size="lg" class="font-semibold text-zinc-900 dark:text-white text-center mt-2">
                        {{ $trainingModule->name }}
                    </flux:text>
                </div>

                <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4 shadow-sm">
                    <div class="flex flex-col items-center gap-2">
                        <flux:icon.information-circle class="w-5 h-5 text-red-500 dark:text-red-400" />
                        <flux:text size="sm" class="text-zinc-900 dark:text-white text-center">
                            <strong class="font-semibold text-red-500">Warning:</strong> This action cannot be undone. All associated data will be permanently deleted.
                        </flux:text>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm" class="flex-1">
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    variant="primary"
                    color="red"
                    size="sm"
                    class="flex-1">
                    Delete Permanently
                </flux:button>
            </div>
        </form>
    </flux:modal>
</flux:table.cell>
@endforeach
                    </flux:table.row>
                    @empty
                    <flux:table.row>
                        <flux:table.cell class="col-span-7" align="center">No Training Modules Yet</flux:table.cell>
                    </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>
