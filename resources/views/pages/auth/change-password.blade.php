<x-layouts::auth :title="__('Change Password - DOST LJS')">
    <div class="flex flex-col gap-6">
        <flux:card class="w-full max-w-md space-y-6">
            <div class="text-center">
                <flux:heading size="xl">Change Password</flux:heading>
                <flux:text variant="subtle">
                    For your security, please change your password before continuing.
                </flux:text>
            </div>

            @if(session('info'))
            <flux:callout color="amber" icon="shield-exclamation">
                <flux:callout.heading>Security Implementation</flux:callout.heading>
                <flux:text>{{ session('info') }}</flux:text>
            </flux:callout>
            @endif

            @if($errors->any())
            <flux:callout color="red" icon="exclamation-triangle">
                <flux:callout.heading>Error</flux:callout.heading>
                <ul class="text-sm text-red-800 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </flux:callout>
            @endif

            <form action="{{ route('password.change.update') }}" method="POST" class="space-y-4" onsubmit="console.log('Form submitting...')">
                @csrf

                <div>
                    <flux:input
                        type="password"
                        name="current_password"
                        label="Current Password"
                        placeholder="Enter your current password"
                        required
                        autocomplete="current-password" />
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
                        autocomplete="new-password" />
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
                        autocomplete="new-password" />
                    @error('password_confirmation')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <flux:callout color="emerald" icon="information-circle">
                    <flux:callout.heading>Password Requirements:</flux:callout.heading>
                    <flux:callout.text>At least 8 characters long with a mix of letters, numbers, and symbols</flux:callout.text>
                </flux:callout>

                <flux:button
                    type="submit"
                    variant="primary"
                    color="sky"
                    class="w-full">
                    Change Password
                </flux:button>
            </form>
        </flux:card>
    </div>
</x-layouts::auth>