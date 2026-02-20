@props(['assignment'])

@php
$isCompleted = $assignment->module?->dateend?->lt(now());
@endphp

<flux:modal name="unassign-{{ $assignment->id }}" class="max-w-md">
    <div class="p-2 bg-white dark:bg-neutral-800">
        <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
            @if($isCompleted)
            <flux:icon.lock-closed class="w-8 h-8 text-neutral-400" />
            @else
            <flux:icon.exclamation-triangle class="w-8 h-8 text-red-500" />
            @endif
        </div>
    </div>

    @if($isCompleted)
    <div class="p-6 space-y-4 bg-white dark:bg-neutral-800 text-center">
        <flux:heading size="lg">Cannot Unassign</flux:heading>
        <flux:text size="base" variant="subtle">
            You cannot unassign <strong>{{ $assignment->employee_name }}</strong> from a completed training module.
        </flux:text>
    </div>
    @else
    <form action="{{ route('hr.assignments.destroy', $assignment) }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
            <flux:heading size="lg" class="text-center">Unassign Employee?</flux:heading>

            <div class="rounded-lg p-4 shadow-sm text-center">
                <flux:text size="sm">You are about to unassign:</flux:text>
                <flux:text size="lg" class="font-semibold mt-2">{{ $assignment->employee_name }}</flux:text>
                <flux:text size="sm" variant="subtle" class="mt-1">{{ $assignment->training_module }}</flux:text>
            </div>

            <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4">
                <div class="flex flex-col items-center gap-2">
                    <flux:icon.information-circle class="w-5 h-5 text-red-500 dark:text-red-400" />
                    <flux:text size="sm" class="text-center">
                        <strong class="text-red-500">Warning:</strong> This action cannot be undone.
                    </flux:text>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" color="red" size="sm" class="flex-1">
                Unassign
            </flux:button>
        </div>
    </form>
    @endif
</flux:modal>