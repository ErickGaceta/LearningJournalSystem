<flux:modal name="shared-user-action" class="max-w-md">
    <div class="p-2 bg-white dark:bg-neutral-800">
        <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full shadow-lg">
            <flux:icon.exclamation-triangle class="w-8 h-8 text-red-500" />
        </div>
    </div>

    <div class="p-6 space-y-4 bg-white dark:bg-neutral-800">
        <flux:heading size="lg" class="text-center">Manage User</flux:heading>

        <div class="rounded-lg p-4 shadow-sm text-center">
            <flux:text size="sm">You are managing:</flux:text>
            <flux:text size="lg" class="font-semibold mt-2"
                x-text="selectedUser
                    ? [selectedUser.first_name, selectedUser.middle_name, selectedUser.last_name].filter(Boolean).join(' ')
                    : ''">
            </flux:text>
            <flux:text size="sm" variant="subtle" x-text="selectedUser?.email"></flux:text>
        </div>

        <!-- Archive -->
        <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4">
            <div class="flex flex-col items-center gap-2">
                <flux:icon.archive-box class="w-5 h-5 text-red-500 dark:text-red-400" />
                <flux:text size="sm" class="text-center">
                    <strong class="text-red-500">Archive:</strong>
                    This user will no longer be able to log in. This action can be undone at the Archive Tab.
                </flux:text>
                <form
                    :action="`{{ url('admin/users') }}/${selectedUser?.id}/archive`"
                    method="POST"
                    class="w-full"
                >
                    @csrf
                    @method('PATCH')
                    <flux:button type="submit" variant="filled" color="red" size="sm" class="w-full">
                        Archive User
                    </flux:button>
                </form>
            </div>
        </div>

        <!-- Delete -->
        <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4">
            <div class="flex flex-col items-center gap-2">
                <flux:icon.trash class="w-5 h-5 text-red-500 dark:text-red-400" />
                <flux:text size="sm" class="text-center">
                    <strong class="text-red-500">Delete Permanently:</strong>
                    This cannot be undone. All associated assignments and documents will also be deleted.
                </flux:text>
                <form
                    :action="`{{ rtrim(route('admin.users.destroy', '_placeholder_'), '_placeholder_') }}${selectedUser?.id}`"
                    method="POST"
                    class="w-full"
                >
                    @csrf
                    @method('DELETE')
                    <flux:button type="submit" variant="filled" color="red" size="sm" class="w-full">
                        Delete Permanently
                    </flux:button>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex border-t border-neutral-200 dark:border-neutral-700">
        <flux:modal.close>
            <flux:button variant="ghost" size="sm" class="w-full">Cancel</flux:button>
        </flux:modal.close>
    </div>
</flux:modal>