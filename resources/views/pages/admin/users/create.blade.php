<x-layouts::app :title="__('Create User - DOST LJS')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div>
            <flux:heading size="xl">Create User</flux:heading>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-3 gap-4">
                <flux:card class="space-y-6">
                    <flux:heading size="lg">Personal Information</flux:heading>

                    <flux:input name="first_name" label="First Name" required />
                    <flux:input name="middle_name" label="Middle Name" />
                    <flux:input name="last_name" label="Last Name" required />
                    <flux:select name="gender" label="Gender" required>
                        <flux:select.option value="Male">Male</flux:select.option>
                        <flux:select.option value="Female">Female</flux:select.option>
                    </flux:select>
                </flux:card>

                <flux:card class="space-y-6">
                    <flux:heading size="lg">Login Information</flux:heading>

                    <flux:input name="username" label="Username" required />
                    <flux:input name="email" label="Email" type="email" required />
                    <flux:select name="id_positions" label="Position" required>
                        @foreach($positions as $pos)
                        <flux:select.option value="{{ $pos->id }}">{{ $pos->positions }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select name="id_division_units" label="Division/ Unit" required>
                        @foreach($divisions as $div)
                        <flux:select.option value="{{ $div->id }}">{{ $div->division_units }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:card>

                <flux:card class="space-y-6">
                    <flux:heading size="lg">Others</flux:heading>

                    <flux:input name="employee_id" label="Employee ID" required />
                    <flux:input name="employee_type" label="Employee Type" required />
                    <flux:select name="user_type" label="User Role" required>
                        <flux:select.option value="user">User</flux:select.option>
                        <flux:select.option value="hr">HR</flux:select.option>
                    </flux:select>
                    <flux:checkbox name="is_active" label="Active" checked />
                </flux:card>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <flux:button variant="ghost" icon="x-mark" size="sm" href="{{ route('admin.users.index') }}">Cancel</flux:button>
                <flux:button type="submit" variant="primary" color="cyan" icon="document-plus" size="sm">Save</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>