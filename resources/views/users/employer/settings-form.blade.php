<x-panel>
    <form method="post" action="{{ route('users.employer.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('patch')

        <div class="flex items-center gap-4">
            <x-profile-logo :user="$user" width="64" class="rounded-xl" />
            <div class="min-w-0">
                <x-input-label for="logo" :value="__('Company Logo')" />
                <x-file-input id="logo" name="logo" accept="image/*" />
                <x-input-error :messages="$errors->get('logo')" />
            </div>
        </div>

        <div>
            <x-input-label for="company_name" :value="__('Company Name')" />
            <x-text-input id="company_name" name="company_name" required :value="old('company_name', $user->name)" />
            <x-input-error :messages="$errors->get('company_name')" />
        </div>

        <div>
            <x-input-label for="company_description" :value="__('Company Description')" />
            <textarea id="company_description" name="company_description" rows="6"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 transition focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-blue-700 dark:focus:ring-blue-700">{{ old('company_description', $profile['company_description'] ?? '') }}</textarea>
            <x-input-error :messages="$errors->get('company_description')" />
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="company_location" :value="__('Location')" />
                <x-text-input id="company_location" name="company_location" :value="old('company_location', $profile['company_location'] ?? '')" />
                <x-input-error :messages="$errors->get('company_location')" />
            </div>

            <div>
                <x-input-label for="industry" :value="__('Industry')" />
                <x-text-input id="industry" name="industry" :value="old('industry', $profile['industry'] ?? '')" />
                <x-input-error :messages="$errors->get('industry')" />
            </div>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="company_size" :value="__('Company Size')" />
                <x-text-input id="company_size" name="company_size" :value="old('company_size', $profile['company_size'] ?? '')" />
                <x-input-error :messages="$errors->get('company_size')" />
            </div>

            <div>
                <x-input-label for="company_website" :value="__('Website')" />
                <x-text-input id="company_website" name="company_website" type="url" :value="old('company_website', $profile['company_website'] ?? '')" />
                <x-input-error :messages="$errors->get('company_website')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Company Profile') }}</x-primary-button>

            @if (session('status') === 'employer-profile-updated')
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</x-panel>
