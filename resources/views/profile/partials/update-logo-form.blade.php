<section>
    <header class="mb-6">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">
            {{ __('Profile Photo') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Update your profile picture. JPG, PNG, GIF, or WebP up to 2MB.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.logo') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="flex items-center gap-5">
            <x-profile-logo :user="$user" width="64" class="rounded-xl shrink-0" />
            <div class="flex-1 min-w-0">
                <x-input-label for="logo" :value="__('New Photo')" />
                <x-file-input id="logo" name="logo" accept="image/*" />
                <x-input-error :messages="$errors->updateLogo->get('logo')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-1">
            <x-primary-button>{{ __('Update Photo') }}</x-primary-button>

            @if (session('status') === 'logo-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Saved.') }}
            </p>
            @endif
        </div>
    </form>
</section>
