@props(['user', 'positions', 'divisions'])

<flux:modal name="edit-user-{{ $user->id }}" class="w-7xl" style="width: 80vw;">
    <div x-data="{ editing: false }">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="flex flex-col gap-0">
            @csrf
            @method('POST')

            <div class="p-6 bg-white dark:bg-neutral-800 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:heading size="lg">{{ $user->full_name }}</flux:heading>
                        <flux:text variant="subtle" class="text-sm mt-1">{{ $user->email }}</flux:text>
                    </div>
                    <div class="flex gap-2">
                        <flux:button x-show="!editing" x-on:click="editing = true" variant="ghost" size="sm" icon="pencil">Edit</flux:button>
                        <flux:button x-show="editing" x-on:click="editing = false" variant="ghost" size="sm" icon="x-mark">Cancel</flux:button>
                    </div>
                </div>

                <flux:separator />

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Personal Information --}}
                    <div class="flex flex-col gap-3">
                        <flux:heading size="sm">Personal Information</flux:heading>
                        <flux:input x-bind:readonly="!editing" name="first_name" label="First Name"
                            value="{{ old('first_name', $user->first_name) }}" required />
                        <flux:input x-bind:readonly="!editing" name="middle_name" label="Middle Name"
                            value="{{ old('middle_name', $user->middle_name) }}" />
                        <flux:input x-bind:readonly="!editing" name="last_name" label="Last Name"
                            value="{{ old('last_name', $user->last_name) }}" required />
                        <flux:select x-bind:disabled="!editing" name="gender" label="Gender" required>
                            <flux:select.option value="">Select gender...</flux:select.option>
                            <flux:select.option value="Male" :selected="old('gender', $user->gender) == 'Male'">Male</flux:select.option>
                            <flux:select.option value="Female" :selected="old('gender', $user->gender) == 'Female'">Female</flux:select.option>
                        </flux:select>
                    </div>

                    {{-- Login Information --}}
                    <div class="flex flex-col gap-3">
                        <flux:heading size="sm">Login Information</flux:heading>
                        <flux:input x-bind:readonly="!editing" name="username" label="Username"
                            value="{{ old('username', $user->username) }}" required />
                        <flux:input x-bind:readonly="!editing" name="email" label="Email" type="email"
                            value="{{ old('email', $user->email) }}" required />
                        <flux:select x-bind:disabled="!editing" name="id_positions" label="Position" required>
                            <flux:select.option value="">Select position...</flux:select.option>
                            @foreach($positions as $pos)
                                <flux:select.option value="{{ $pos->id }}"
                                    :selected="old('id_positions', $user->id_positions) == $pos->id">
                                    {{ $pos->positions }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:select x-bind:disabled="!editing" name="id_division_units" label="Division/Unit" required>
                            <flux:select.option value="">Select division...</flux:select.option>
                            @foreach($divisions as $div)
                                <flux:select.option value="{{ $div->id }}"
                                    :selected="old('id_division_units', $user->id_division_units) == $div->id">
                                    {{ $div->division_units }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    {{-- Others --}}
                    <div class="flex flex-col gap-3">
                        <flux:heading size="sm">Others</flux:heading>
                        <flux:input x-bind:readonly="!editing" name="employee_id" label="Employee ID"
                            value="{{ old('employee_id', $user->employee_id) }}" required />
                        <flux:input x-bind:readonly="!editing" name="employee_type" label="Employee Type"
                            value="{{ old('employee_type', $user->employee_type) }}" required />
                        <flux:select x-bind:disabled="!editing" name="user_type" label="User Role" required>
                            <flux:select.option value="">Select role...</flux:select.option>
                            <flux:select.option value="user" :selected="old('user_type', $user->user_type) == 'user'">User</flux:select.option>
                            <flux:select.option value="hr" :selected="old('user_type', $user->user_type) == 'hr'">HR</flux:select.option>
                        </flux:select>
                        <flux:checkbox x-bind:disabled="!editing" name="is_active" label="Active" value="1"
                            :checked="old('is_active', $user->is_active)" />
                    </div>
                </div>
            </div>

            <div x-show="editing"
                class="bg-white dark:bg-neutral-800 px-6 py-3 flex gap-2 border-t border-neutral-200 dark:border-neutral-700">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm" class="flex-1">Close</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" color="sky" size="sm" icon="arrow-up-on-square" class="flex-1">
                    Save Changes
                </flux:button>
            </div>

            <div x-show="!editing"
                class="bg-white dark:bg-neutral-800 px-6 py-3 flex justify-end border-t border-neutral-200 dark:border-neutral-700">
                <flux:modal.close>
                    <flux:button variant="ghost" size="sm">Close</flux:button>
                </flux:modal.close>
            </div>
        </form>
    </div>
</flux:modal>