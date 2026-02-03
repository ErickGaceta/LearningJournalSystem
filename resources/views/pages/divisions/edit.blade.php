<x-layouts::app :title="'Edit Division/Unit: ' . $division->division_units">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-heading">Edit Division/Unit</h1>
            <p class="text-sm text-neutral-600">Update division/unit information</p>
        </div>

        <div class="rounded-xl border border-neutral-200 p-6 max-w-2xl">
            <form action="{{ route('divisions.update', $division) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="division_units" class="block mb-2 text-base font-medium text-heading">Division/Unit Name</label>
                    <input type="text"
                        id="division_units"
                        name="division_units"
                        class="bg-neutral-secondary-medium border border-default-medium text-heading w-full text-sm rounded-xl focus:ring-brand focus:border-brand block px-3 py-2 shadow-xs placeholder:text-body"
                        placeholder="Enter division/unit name"
                        value="{{ old('division_units', $division->division_units) }}"
                        required />
                    @error('division_units')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button :href="route('divisions.index')" variant="filled">Cancel</flux:button>
                    <flux:button type="submit" variant="primary" color="sky" icon="arrow-up-on-square">Update</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>