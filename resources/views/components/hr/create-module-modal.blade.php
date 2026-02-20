<flux:modal name="create-module" class="max-w-lg">
    <form action="{{ route('hr.modules.store') }}" method="POST" class="flex flex-col gap-0">
        @csrf

        <div class="p-6 bg-white dark:bg-neutral-800 space-y-4">
            <div>
                <flux:heading size="lg">Create Training Module</flux:heading>
                <flux:text variant="subtle" class="text-sm mt-1">Fill in the details for the new training module.</flux:text>
            </div>

            <flux:separator />

            <div class="flex flex-col gap-3">
                <flux:heading size="sm">Basic Information</flux:heading>
                <flux:input name="title" label="Training Title" required />
                <flux:input name="venue" label="Venue" required />
                <flux:input name="conductedby" label="Conducted / Sponsored By" required />
            </div>

            <flux:separator />

            <div class="flex flex-col gap-3">
                <flux:heading size="sm">Additional Information</flux:heading>
                <div class="grid grid-cols-2 gap-3">
                    <flux:input name="datestart" type="date" label="Date Start" required />
                    <flux:input name="dateend" type="date" label="Date End" required />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <flux:input name="hours" label="Training Hours" required />
                    <flux:input name="registration_fee" label="Registration Fee" placeholder="N/A if free" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" color="teal" size="sm" icon="folder-plus" class="flex-1">
                Save
            </flux:button>
        </div>
    </form>
</flux:modal>