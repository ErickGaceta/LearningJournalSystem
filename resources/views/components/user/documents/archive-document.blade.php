@props(['action', 'modalName'])

<flux:modal :name="$modalName" class="min-w-88">
    <form action="{{ $action }}" method="POST">
        @csrf
        @method('PATCH')

        <flux:heading size="lg">Archive this journal?</flux:heading>
        <flux:text class="mt-2">
            This learning journal will be archived and hidden from your active records.<br>
            You can restore it later from your archived documents.
        </flux:text>

        <div class="flex gap-4 mt-4">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="filled"
                class="bg-amber-500 hover:bg-amber-600 text-white dark:bg-amber-500 dark:hover:bg-amber-600">
                Archive
            </flux:button>
        </div>
    </form>
</flux:modal>