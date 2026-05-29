<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Reset your password
        </h2>
        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
            {{ __("No problem. Enter your email address and we'll send you a reset link.") }}
        </p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="pt-1">
            <x-primary-button class="w-full justify-center py-3 text-sm normal-case tracking-normal font-semibold">
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}"
                class="text-sm text-gray-500 hover:text-gray-400 dark:hover:text-gray-300 underline underline-offset-2 transition-colors duration-150">
                {{ __('Back to Sign In') }}
            </a>
        </div>
    </form>
</x-guest-layout>
