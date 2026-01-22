<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to register')" />

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Employee ID -->
            <flux:input
                name="employee_id"
                :label="__('Employee ID')"
                :value="old('employee_id')"
                type="text"
                required
                autofocus
            />

            <!-- First Name -->
            <flux:input
                name="fname"
                :label="__('First Name')"
                :value="old('fname')"
                type="text"
                required
                autocomplete="given-name"
            />

            <!-- Middle Name -->
            <flux:input
                name="mname"
                :label="__('Middle Name (Optional)')"
                :value="old('mname')"
                type="text"
                autocomplete="additional-name"
            />

            <!-- Last Name -->
            <flux:input
                name="lname"
                :label="__('Last Name')"
                :value="old('lname')"
                type="text"
                required
                autocomplete="family-name"
            />

            <!-- Gender -->
            <flux:select
                name="gender"
                :label="__('Gender')"
                required
            >
                <option value="">{{ __('Select Gender') }}</option>
                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                <option value="Not specified" {{ old('gender') == 'Not specified' ? 'selected' : '' }}>{{ __('Prefer not to say') }}</option>
            </flux:select>

            <!-- Username -->
            <flux:input
                name="username"
                :label="__('Username')"
                :value="old('username')"
                type="text"
                required
                autocomplete="username"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </form>

        @if (Route::has('login'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Already have an account?') }}</span>
                <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>