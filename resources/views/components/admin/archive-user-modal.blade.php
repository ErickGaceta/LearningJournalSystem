<flux:modal name="shared-archive-user" class="max-w-md">
    <form :action="`{{ rtrim(route('admin.users.archive', '_placeholder_'), '_placeholder_') }}${selectedUser?.id}`" method="POST">
        @csrf
        @method('PATCH')

        <div class="p-2 bg-white dark:bg-neutral-800">
            <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
                <flux:icon.exclamation-triangle class="w-8 h-8 text-red-500" />
            </div>
        </div>

        <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
            <flux:heading size="lg" class="text-center">Archive User?</flux:heading>

            <div class="rounded-lg p-4 shadow-sm text-center">
                <flux:text size="sm">You are about to archive:</flux:text>
                <flux:text size="lg" class="font-semibold mt-2"
                    x-text="selectedUser
                        ? [selectedUser.first_name, selectedUser.middle_name, selectedUser.last_name].filter(Boolean).join(' ')
                        : ''">
                </flux:text>
                <flux:text size="sm" variant="subtle" x-text="selectedUser?.email"></flux:text>
            </div>

            <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4">
                <div class="flex flex-col items-center gap-2">
                    <flux:icon.information-circle class="w-5 h-5 text-red-500 dark:text-red-400" />
                    <flux:text size="sm" class="text-center">
                        <strong class="text-red-500">Warning:</strong>
                        This user will no longer be able to log in. This action can be undone by an administrator.
                    </flux:text>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" color="red" size="sm" class="flex-1">
                Archive
            </flux:button>
        </div>
    </form>
</flux:modal>