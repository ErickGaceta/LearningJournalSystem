@props(['positions', 'divisions'])

<flux:modal name="create-user" class="max-w-7xl" style="width: 80vw;">
    <form action="{{ route('admin.users.store') }}" method="POST" class="flex flex-col gap-0">
        @csrf

        <div class="p-6 bg-white dark:bg-neutral-800 space-y-4">
            <div>
                <flux:heading size="lg">Create User</flux:heading>
                <flux:text variant="subtle" class="text-sm mt-1">Fill in the details for the new user account.</flux:text>
            </div>

            <flux:separator />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Personal Information --}}
                <div class="flex flex-col gap-3">
                    <flux:heading size="sm">Personal Information</flux:heading>
                    <flux:input name="first_name" label="First Name" value="{{ old('first_name') }}" required />
                    <flux:input name="middle_name" label="Middle Name" value="{{ old('middle_name') }}" />
                    <flux:input name="last_name" label="Last Name" value="{{ old('last_name') }}" required />
                    <flux:select name="gender" label="Gender" required>
                        <flux:select.option value="">Select gender...</flux:select.option>
                        <flux:select.option value="Male" :selected="old('gender') == 'Male'">Male</flux:select.option>
                        <flux:select.option value="Female" :selected="old('gender') == 'Female'">Female</flux:select.option>
                    </flux:select>
                </div>

                {{-- Login Information --}}
                <div class="flex flex-col gap-3">
                    <flux:heading size="sm">Login Information</flux:heading>
                    <flux:input name="username" label="Username" value="{{ old('username') }}" required />
                    <flux:input name="email" label="Email" type="email" value="{{ old('email') }}" required />
                    <flux:select name="id_positions" label="Position" required>
                        <flux:select.option value="">Select position...</flux:select.option>
                        @foreach($positions as $pos)
                            <flux:select.option value="{{ $pos->id }}" :selected="old('id_positions') == $pos->id">
                                {{ $pos->positions }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select name="id_division_units" label="Division/Unit" required>
                        <flux:select.option value="">Select division...</flux:select.option>
                        @foreach($divisions as $div)
                            <flux:select.option value="{{ $div->id }}" :selected="old('id_division_units') == $div->id">
                                {{ $div->division_units }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Others --}}
                <div class="flex flex-col gap-3">
                    <flux:heading size="sm">Others</flux:heading>
                    <flux:input name="employee_id" label="Employee ID" value="{{ old('employee_id') }}" required />
                    <flux:input name="employee_type" label="Employee Type" value="{{ old('employee_type') }}" required />
                    <flux:select name="user_type" label="User Role" required>
                        <flux:select.option value="">Select role...</flux:select.option>
                        <flux:select.option value="user" :selected="old('user_type') == 'user'">User</flux:select.option>
                        <flux:select.option value="hr" :selected="old('user_type') == 'hr'">HR</flux:select.option>
                    </flux:select>
                    <flux:checkbox name="is_active" label="Active" value="1" checked />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" class="flex-1">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" color="cyan" size="sm" icon="document-plus" class="flex-1">
                Save User
            </flux:button>
        </div>
    </form>
</flux:modal>