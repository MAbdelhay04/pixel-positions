<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Check your inbox
        </h2>
        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
            {{ __("Thanks for signing up! Before getting started, please verify your email address by clicking the link we sent you. Didn't receive it? We'll send another.") }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 flex items-center gap-2 px-4 py-3 rounded-lg border border-green-700/40 bg-green-900/20 dark:border-green-800/50 dark:bg-green-900/20 text-sm font-medium text-green-600 dark:text-green-400">
            <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="cursor-pointer text-sm text-gray-500 underline underline-offset-2 transition-colors duration-150 hover:text-gray-700 dark:hover:text-gray-300">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
