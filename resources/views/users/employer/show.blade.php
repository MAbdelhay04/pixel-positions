<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <x-profile-logo :user="$user" width="56" class="rounded-xl" />
                <div>
                    <h2 class="text-xl font-bold leading-tight">{{ $user->name }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Company Profile') }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('companies.jobs', $user) }}"
                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/10">
                    {{ __('Open Jobs') }}
                </a>

                @if ($canEdit ?? false)
                <a href="{{ route('users.employer.edit') }}"
                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/10">
                    {{ __('Edit Profile') }}
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto grid max-w-5xl gap-6 sm:px-6 lg:grid-cols-[1fr_18rem] lg:px-8">
            <x-panel>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ __('About Company') }}</h3>
                <p class="mt-4 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">
                    {{ $profile['company_description'] ?? __('No company description has been added yet.') }}
                </p>
            </x-panel>

            <aside class="space-y-6">
                <x-panel>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Company Details') }}</h3>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Location') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $profile['company_location'] ?? __('Not set') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Industry') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $profile['industry'] ?? __('Not set') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Company Size') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $profile['company_size'] ?? __('Not set') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Website') }}</dt>
                            <dd class="mt-1 break-words font-medium text-gray-900 dark:text-white">
                                @if (! empty($profile['company_website']))
                                    <a href="{{ $profile['company_website'] }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                        {{ $profile['company_website'] }}
                                    </a>
                                @else
                                    {{ __('Not set') }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </x-panel>
            </aside>
        </div>
    </div>
</x-app-layout>
