<x-layouts::guest :title="__('Change Password - DOST LJS')">
    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4">
        <flux:card class="w-full max-w-md space-y-6">
            <div class="text-center">
                <flux:heading size="xl">Change Password</flux:heading>
                <p class="mt-2 text-sm text-gray-600">
                    For your security, please change your password before continuing.
                </p>
            </div>

            {{-- DEBUG: Show route --}}
            <div class="text-xs bg-yellow-100 p-2 rounded">
                Form will POST to: {{ route('password.update') }}
            </div>

            @if(session('info'))
            <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                <p class="text-sm text-blue-800">{{ session('info') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                <ul class="text-sm text-red-800 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4" onsubmit="console.log('Form submitting...')">
                @csrf

                <div>
                    <flux:input 
                        type="password" 
                        name="current_password" 
                        label="Current Password" 
                        placeholder="Enter your current password"
                        required 
                        autocomplete="current-password"
                    />
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input 
                        type="password" 
                        name="password" 
                        label="New Password" 
                        placeholder="Enter new password"
                        required 
                        autocomplete="new-password"
                    />
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input 
                        type="password" 
                        name="password_confirmation" 
                        label="Confirm New Password" 
                        placeholder="Confirm new password"
                        required 
                        autocomplete="new-password"
                    />
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-lg bg-gray-50 p-3">
                    <p class="text-xs text-gray-600 font-medium mb-2">Password Requirements:</p>
                    <ul class="text-xs text-gray-500 space-y-1 list-disc list-inside">
                        <li>At least 8 characters long</li>
                        <li>Mix of letters, numbers, and symbols recommended</li>
                    </ul>
                </div>

                <flux:button 
                    type="submit" 
                    variant="primary" 
                    class="w-full"
                >
                    Change Password
                </flux:button>
            </form>

            <div class="text-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                        Logout instead
                    </button>
                </form>
            </div>
        </flux:card>
    </div>
</x-layouts::guest>