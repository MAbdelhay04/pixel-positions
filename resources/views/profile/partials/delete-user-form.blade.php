<section>
    <header class="mb-6">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">
            {{ __('Delete Account') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please
            download any data you wish to retain beforehand.') }}
        </p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>
            <p class="text-sm text-gray-500 mb-6">
                {{ __('This action is permanent. Please enter your password to confirm.') }}
            </p>

            <div class="mb-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <div class="w-3/4">
                    <x-text-input id="password" name="password" type="password" placeholder="{{ __('Your password') }}" />
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-danger-button>
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
