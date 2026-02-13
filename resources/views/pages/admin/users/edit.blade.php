<x-layouts::app :title="__('Edit Profile - DOST LJS')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div>
            <flux:heading size="xl">Edit Profile</flux:heading>
        </div>

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

        {{-- Success message --}}
        @if(session('success'))
        <div class="rounded-lg bg-green-50 border border-green-300 p-4">
            <div class="flex items-start gap-3">
                <svg class="size-5 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-3 gap-4">
                <flux:card class="space-y-6">
                    <flux:heading size="lg">Personal Information</flux:heading>

                    <div>
                        <flux:input 
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
                            name="middle_name" 
                            label="Middle Name"
                            value="{{ old('middle_name', $user->middle_name) }}"
                        />
                    </div>

                    <div>
                        <flux:input 
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
                        <flux:select name="gender" label="Gender" required>
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
                    <flux:heading size="lg">Contact & Work Information</flux:heading>

                    <div>
                        <flux:input 
                            name="username" 
                            label="Username"
                            value="{{ $user->username }}"
                            readonly
                            disabled
                        />
                        <p class="mt-1 text-xs text-gray-500">Username cannot be changed</p>
                    </div>

                    <div>
                        <flux:input 
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
                        <flux:select name="id_positions" label="Position" disabled>
                            <option value="">Select position...</option>
                            @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ $user->id_positions == $pos->id ? 'selected' : '' }}>
                                {{ $pos->positions }}
                            </option>
                            @endforeach
                        </flux:select>
                        <p class="mt-1 text-xs text-gray-500">Contact HR to update position</p>
                    </div>

                    <div>
                        <flux:select name="id_division_units" label="Division/Unit" disabled>
                            <option value="">Select division...</option>
                            @foreach($divisions as $div)
                            <option value="{{ $div->id }}" {{ $user->id_division_units == $div->id ? 'selected' : '' }}>
                                {{ $div->division_units }}
                            </option>
                            @endforeach
                        </flux:select>
                        <p class="mt-1 text-xs text-gray-500">Contact HR to update division</p>
                    </div>
                </flux:card>

                <flux:card class="space-y-6">
                    <flux:heading size="lg">Change Password</flux:heading>

                    <div>
                        <flux:input 
                            name="current_password" 
                            label="Current Password"
                            type="password"
                            placeholder="Leave blank to keep current password"
                        />
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:input 
                            name="password" 
                            label="New Password"
                            type="password"
                            placeholder="Leave blank to keep current password"
                        />
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:input 
                            name="password_confirmation" 
                            label="Confirm New Password"
                            type="password"
                            placeholder="Confirm your new password"
                        />
                    </div>

                    <div class="pt-4 border-t">
                        <p class="text-xs text-gray-600">
                            <strong>Employee ID:</strong> {{ $user->employee_id }}<br>
                            <strong>Employee Type:</strong> {{ $user->employee_type }}<br>
                            <strong>Role:</strong> {{ ucfirst($user->user_type) }}
                        </p>
                    </div>
                </flux:card>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <flux:button variant="ghost" icon="x-mark" size="sm" :href="route('user.trainings.index')">Cancel</flux:button>
                <flux:button type="submit" variant="primary" color="cyan" icon="check" size="sm">Update Profile</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>