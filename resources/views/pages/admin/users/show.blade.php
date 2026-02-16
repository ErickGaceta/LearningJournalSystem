<x-layouts::app :title="__('Edit User - DOST LJS')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="{ editing: false }">

        {{-- Display validation errors --}}
        @if($errors->any())
        <div class="rounded-lg bg-red-50 border border-red-300 p-4">
            <div class="flex items-start gap-3">
                <svg class="size-5 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <div class="flex-1">
                    <strong class="font-medium text-red-900">Please fix the following errors:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-800">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Edit User</flux:heading>
            </div>
            <div class="flex gap-2">
                <!-- Toggle Edit / Cancel -->
                   <flux:button
                    x-show="!editing"
                    x-on:click="editing = true"
                    variant="ghost"
                    icon="pencil">
                    Edit
                </flux:button>
                <flux:button
                    x-show="editing"
                    x-on:click="editing = false"
                    variant="ghost"
                    icon="x-mark">
                    Cancel
                </flux:button>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user
        ) }}" method="POST">
            @csrf

            <div class="grid grid-cols-3 gap-4">
                <flux:card class="space-y-6">
                    <flux:heading size="lg">Personal Information</flux:heading>

                    <div>
                        <flux:input
                            x-bind:readonly="!editing"
                            name="first_name"
                            label="First Name"
                            value="{{ old('first_name', $user->first_name) }}"
                            required
                        />
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:input
                            x-bind:readonly="!editing"
                            name="middle_name"
                            label="Middle Name"
                           value="{{ old('middle_name', $user->middle_name) }}"
                        />
                    </div>

                    <div>
                        <flux:input
                            x-bind:readonly="!editing"
                            name="last_name"
                            label="Last Name"
                            value="{{ old('last_name', $user->last_name) }}"
                            required
                        />
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:select
                            x-bind:disabled="!editing"
                            name="gender"
                            label="Gender"
                            required>
                            <option value="">Select gender...</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </flux:select>
                        @error('gender')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </flux:card>

                <flux:card class="space-y-6">
                    <flux:heading size="lg">Login Information</flux:heading>

                    <div>
                        <flux:input
                            x-bind:readonly="!editing"
                            name="username"
                            label="Username"
                            value="{{ old('username', $user->username) }}"
                            required
                        />
                        @error('username')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:input
                            x-bind:readonly="!editing"
                            name="email"
                            label="Email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                        />
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:select
                            x-bind:disabled="!editing"
                            name="id_positions"
                            label="Position"
                            required>
                            <option value="">Select position...</option>
                            @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ old('id_positions', $user->id_positions) == $pos->id ? 'selected' : '' }}>
                                {{ $pos->positions }}
                            </option>
                            @endforeach
                        </flux:select>
                        @error('id_positions')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:select
                            x-bind:disabled="!editing"
                            name="id_division_units"
                            label="Division/Unit"
                            required>
                            <option value="">Select division...</option>
                            @foreach($divisions as $div)
                            <option value="{{ $div->id }}" {{ old('id_division_units', $user->id_division_units) == $div->id ? 'selected' : '' }}>
                                {{ $div->division_units }}
                            </option>
                            @endforeach
                        </flux:select>
                        @error('id_division_units')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </flux:card>

                <flux:card class="space-y-6">
                    <flux:heading size="lg">Others</flux:heading>

                    <div>
                        <flux:input
                            x-bind:readonly="!editing"
                            name="employee_id"
                            label="Employee ID"
                            value="{{ old('employee_id', $user->employee_id) }}"
                            required
                        />
                        @error('employee_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:input
                            x-bind:readonly="!editing"
                            name="employee_type"
                            label="Employee Type"
                            value="{{ old('employee_type', $user->employee_type) }}"
                            required
                        />
                        @error('employee_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:select
                            x-bind:disabled="!editing"
                            name="user_type"
                            label="User Role"
                            required>
                            <option value="">Select role...</option>
                            <option value="user" {{ old('user_type', $user->user_type) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="hr" {{ old('user_type', $user->user_type) == 'hr' ? 'selected' : '' }}>HR</option>
                        </flux:select>
                        @error('user_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:checkbox
                            x-bind:disabled="!editing"
                            name="is_active"
                            label="Active"
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                        />
                    </div>
                </flux:card>
            </div>

            <div class="flex justify-end gap-2 mt-4" x-show="editing">
                <flux:button icon="arrow-up-on-square" type="submit" variant="primary" color="sky">
                    Save Changes
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
