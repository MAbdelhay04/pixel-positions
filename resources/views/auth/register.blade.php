<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Create an account
        </h2>
        <p class="mt-1 text-sm text-gray-500">Join us today — it's free.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" placeholder="Your full name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                required autocomplete="email" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" type="text" name="username" :value="old('username')"
                required autocomplete="username" placeholder="@handle" />
            <x-input-error :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="logo" :value="__('Profile Image')" />
            <x-file-input id="logo" name="logo" accept="image/*" class="mt-0.5" />
            <x-input-error :messages="$errors->get('logo')" />
        </div>

        <div>
            <x-input-label :value="__('I am a')" />
            <x-radio-group name="role" :options="[
                ['value' => 'employer',  'label' => 'Employer'],
                ['value' => 'candidate', 'label' => 'Candidate'],
            ]" />
            <x-input-error :messages="$errors->get('role')" />
        </div>

        <div class="space-y-5 border-t border-gray-200 pt-4 dark:border-white/10">
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password"
                    required autocomplete="new-password" placeholder="Min. 8 characters" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                    required autocomplete="new-password" placeholder="Repeat your password" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <div class="pt-1">
            <x-primary-button class="w-full justify-center py-3 text-sm normal-case tracking-normal font-semibold">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-1">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 transition-colors duration-150 hover:text-gray-900 dark:hover:text-white">
                {{ __('Already registered?') }}
                <span class="ml-1 text-gray-800 underline underline-offset-2 dark:text-white">Sign in</span>
            </a>
        </div>
    </form>
</x-guest-layout>
