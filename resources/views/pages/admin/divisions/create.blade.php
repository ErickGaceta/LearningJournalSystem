<x-layouts::app :title="__('Create Division/Unit')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-heading">Create New Division/Unit</h1>
            <p class="text-sm text-neutral-600">Add a new division or unit to the system</p>
        </div>

        <flux:card class="size-max dark:hover:bg-zinc-700">
            <form action="{{ route('admin.divisions.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <flux:label class="py-4">Division/Unit Name</flux:label>
                    <flux:input type="text"
                        id="division_units"
                        size="sm"
                        name="division_units"
                        value="{{ old('division_units') }}"
                        placeholder="Division Name"
                        required />
                    @error('division_units')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button :href="route('admin.divisions.index')" variant="ghost">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="folder-arrow-down" color="green" />
                </div>
            </flux:card>
        </div>
    </div>
</x-layouts::app>