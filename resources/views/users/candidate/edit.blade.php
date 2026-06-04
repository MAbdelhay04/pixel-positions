<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold leading-tight">{{ __('Edit Candidate Profile') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            </div>

            <a href="{{ route('users.candidate.show') }}"
                class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/10">
                {{ __('View Profile') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <x-panel>
                <form method="post" action="{{ route('users.candidate.update') }}" class="space-y-5">
                    @csrf
                    @method('patch')

                    <div>
                        <x-input-label for="headline" :value="__('Headline')" />
                        <x-text-input id="headline" name="headline" :value="old('headline', $profile['headline'] ?? '')" />
                        <x-input-error :messages="$errors->get('headline')" />
                    </div>

                    <div>
                        <x-input-label for="bio" :value="__('Bio')" />
                        <textarea id="bio" name="bio" rows="5"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 transition focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-blue-700 dark:focus:ring-blue-700">{{ old('bio', $profile['bio'] ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('bio')" />
                    </div>

                    <div>
                        <x-input-label for="work_experience" :value="__('Work Experience')" />
                        <textarea id="work_experience" name="work_experience" rows="6"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 transition focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-blue-700 dark:focus:ring-blue-700">{{ old('work_experience', $profile['work_experience'] ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('work_experience')" />
                    </div>

                    <div>
                        <x-input-label for="education" :value="__('Education')" />
                        <textarea id="education" name="education" rows="5"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 transition focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-blue-700 dark:focus:ring-blue-700">{{ old('education', $profile['education'] ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('education')" />
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <x-input-label for="location" :value="__('Location')" />
                            <x-text-input id="location" name="location" :value="old('location', $profile['location'] ?? '')" />
                            <x-input-error :messages="$errors->get('location')" />
                        </div>

                        <div>
                            <x-input-label for="availability" :value="__('Availability')" />
                            <x-text-input id="availability" name="availability" :value="old('availability', $profile['availability'] ?? '')" />
                            <x-input-error :messages="$errors->get('availability')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="portfolio_url" :value="__('Portfolio URL')" />
                        <x-text-input id="portfolio_url" name="portfolio_url" type="url" :value="old('portfolio_url', $profile['portfolio_url'] ?? '')" />
                        <x-input-error :messages="$errors->get('portfolio_url')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save Profile') }}</x-primary-button>

                        @if (session('status') === 'candidate-profile-updated')
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </form>
            </x-panel>
        </div>
    </div>
</x-app-layout>
