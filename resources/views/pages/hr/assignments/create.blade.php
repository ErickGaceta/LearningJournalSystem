<x-layouts::app :title="__('Assign Training')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Assign Training</flux:heading>
        <div>
            <form action="{{ route('hr.assignments.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <flux:select name="user_id" placeholder="Choose user...">
                    @foreach($users as $user)
                        <flux:select.option value="{{ $user->id }}">{{ $user->full_name }}</flux:select.option>
                    @endforeach
                </flux:select>

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