<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Welcome back
        </h2>
        <p class="mt-1 text-sm text-gray-500">Sign in to your account to continue.</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" :value="__('Password')" class="mb-0" />
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-xs text-gray-500 underline underline-offset-2 transition-colors duration-150 hover:text-gray-700 dark:hover:text-gray-300">
                    {{ __('Forgot password?') }}
                </a>
                @endif
            </div>
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 rounded text-blue-600 transition-colors duration-150 border-gray-300 bg-white focus:ring-blue-600 focus:ring-offset-white dark:border-white/20 dark:bg-white/5 dark:focus:ring-blue-700 dark:focus:ring-offset-black">
                <span class="text-sm text-gray-500 group-hover:text-gray-400 transition-colors duration-150">
                    {{ __('Remember me') }}
                </span>
            </label>
        </div>

        <div class="pt-1">
            <x-primary-button class="w-full justify-center py-3 text-sm normal-case tracking-normal font-semibold">
                {{ __('Sign In') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-1">
            <a href="{{ route('register') }}"
                class="text-sm text-gray-500 transition-colors duration-150 hover:text-gray-900 dark:hover:text-white">
                {{ __("Don't have an account?") }}
                <span class="ml-1 text-gray-800 underline underline-offset-2 dark:text-white">Sign up</span>
            </a>
        </div>
    </form>
</x-guest-layout>
