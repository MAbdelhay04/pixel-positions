<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold leading-tight">{{ __('Candidate Profile') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $user->name }}</p>
            </div>

            @if ($canEdit ?? false)
            <a href="{{ route('users.candidate.edit') }}"
                class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/10">
                {{ __('Edit Profile') }}
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto grid max-w-5xl gap-6 sm:px-6 lg:grid-cols-[1fr_18rem] lg:px-8">
            <section class="space-y-6">
                <x-panel>
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        {{ $profile['headline'] ?? __('Candidate') }}
                    </p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                    <p class="mt-4 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $profile['bio'] ?? __('No bio has been added yet.') }}
                    </p>
                </x-panel>

                <x-panel>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ __('Work Experience') }}</h3>
                    <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $profile['work_experience'] ?? __('No work experience has been added yet.') }}
                    </p>
                </x-panel>

                <x-panel>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ __('Education') }}</h3>
                    <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $profile['education'] ?? __('No education has been added yet.') }}
                    </p>
                </x-panel>
            </section>

            <aside class="space-y-6">
                <x-panel>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Details') }}</h3>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Location') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $profile['location'] ?? __('Not set') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Availability') }}</dt>
                            <dd class="mt-1 font-medium text-gray-900 dark:text-white">{{ $profile['availability'] ?? __('Not set') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">{{ __('Portfolio') }}</dt>
                            <dd class="mt-1 break-words font-medium text-gray-900 dark:text-white">
                                @if (! empty($profile['portfolio_url']))
                                    <a href="{{ $profile['portfolio_url'] }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                        {{ $profile['portfolio_url'] }}
                                    </a>
                                @else
                                    {{ __('Not set') }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </x-panel>

                @if ($user->skills->isNotEmpty())
                    <x-panel>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Skills') }}</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($user->skills as $skill)
                                <x-skill :skill="$skill" />
                            @endforeach
                        </div>
                    </x-panel>
                @endif
            </aside>
        </div>
    </div>
</x-app-layout>
