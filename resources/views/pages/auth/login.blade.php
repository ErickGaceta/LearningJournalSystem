<x-layouts::auth>
    <div class="flex flex-col gap-6">
        @if(session('success'))
        <flux:callout color="green" icon="check-circle">
            <flux:callout.heading>Success</flux:callout.heading>
            <flux:text>{{ session('success') }}</flux:text>
        </flux:callout>
        @endif

        @if(session('status'))
        <flux:callout color="green" icon="presentation-chart-line">
            <flux:callout.heading>Status</flux:callout.heading>
            <flux:text>{{ session('status') }}</flux:text>
        </flux:callout>
        @endif
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="login"
                :label="__('Username/Email address')"
                :value="old('email')"
                required
                autofocus
                placeholder="email@example.com" />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable />

                @if (Route::has('password.request'))
                <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>