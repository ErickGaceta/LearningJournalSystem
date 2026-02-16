<x-layouts::app :title="__('Assign Training')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Assign Training</flux:heading>
        <div>
            <form action="{{ route('hr.assignments.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <flux:fieldset>
                    <flux:legend>Select Users</flux:legend>
                    <flux:checkbox.group>
                        <flux:checkbox.all label="Select all" class="grid grid-cols-2" />
                        @foreach($users as $user)
                            <flux:checkbox
                                name="user_ids[]"
                                value="{{ $user->id }}"
                                label="{{ $user->full_name }}"
                            />
                        @endforeach
                    </flux:checkbox.group>
                </flux:fieldset>

                <flux:select name="module_id" placeholder="Choose training module...">
                    @foreach($modules as $mod)
                        <flux:select.option value="{{ $mod->id }}">{{ $mod->title }}</flux:select.option>
                    @endforeach
                </flux:select>

                <div class="flex justify-end">
                    <flux:button type="submit" icon="folder-plus" variant="primary" color="teal">Save</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>