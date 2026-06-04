<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-gray-900 dark:text-white">
                    {{ $job->title }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if ($job->employer)
                    <a href="{{ route('companies.show', $job->employer) }}" class="transition-colors hover:text-indigo-700 dark:hover:text-indigo-400">
                        {{ $job->employer->name }}
                    </a>
                    @else
                    {{ __('Hiring team') }}
                    @endif
                </p>
            </div>

            <div class="flex gap-3">
                <x-secondary-button type="button" onclick="window.location='{{ route('jobs.index') }}'">
                    {{ __('Back') }}
                </x-secondary-button>

                @can ('update' , $job)
                <x-primary-button type="button" onclick="window.location='{{ route('jobs.edit', $job) }}'">
                    {{ __('Edit Job') }}
                </x-primary-button>

                <form method="POST" action="{{ route('jobs.destroy', $job) }}"
                    onsubmit="return confirm('{{ __('Are you sure you want to delete this job?') }}')">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>
                        {{ __('Delete Job') }}
                    </x-danger-button>
                </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[1fr_18rem]">

            <section class="space-y-6">
                <x-job-card-wide :job="$job" :withLink="false" :withApply="false" />

                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                        {{ __('Description') }}
                    </h3>
                    <div class="mt-4 whitespace-pre-line text-sm leading-7 text-gray-600 dark:text-gray-300">
                        {{ $job->description }}
                    </div>
                </div>
            </section>

            <aside class="space-y-4">
                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                        {{ __('Job Details') }}
                    </h3>

                    <dl class="mt-5 space-y-4 text-sm">
                        <div>
                            <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('Location') }}</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">{{ $job->location->label() }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('Type') }}</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">{{ $job->type->label() }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                            <dd class="mt-1">
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $job->status->color() }}">
                                    {{ $job->status->label() }}
                                </span>
                            </dd>
                        </div>
                        @if ($job->salary_range)
                        <div>
                            <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('Salary') }}</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">{{ $job->salary_range }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <x-job-status-update :job="$job" />

                @if ($job->employer)
                <a href="{{ route('companies.show', $job->employer) }}"
                    class="flex items-center justify-between gap-3 rounded-lg border p-4 text-sm font-semibold transition-colors duration-200 border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50
                        dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white/20 dark:hover:bg-white/10">
                    <span>{{ __('View Company Profile') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('companies.jobs', $job->employer) }}"
                    class="flex items-center justify-between gap-3 rounded-lg border p-4 text-sm font-semibold transition-colors duration-200 border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50
                        dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white/20 dark:hover:bg-white/10">
                    <span>{{ __('More Jobs From This Company') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @endif

                {{-- Employer: view applications --}}
                @auth
                @can('viewApplications', $job)
                <a href="{{ route('applications.index', $job) }}"
                    class="flex items-center justify-between gap-3 rounded-lg border p-4 text-sm font-semibold transition-colors duration-200 border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50
                        dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white/20 dark:hover:bg-white/10">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-5.477-3.716M9 20H4v-2a4 4 0 015.477-3.716M15 11a4 4 0 10-8 0 4 4 0 008 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('View Applications') }}
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @elsecan('apply', $job)
                <x-primary-button x-data="" x-on:click="$dispatch('open-modal', 'apply-{{ $job->id }}')"
                    class="w-full justify-center py-3">
                    {{ __('Apply Now') }}
                </x-primary-button>
                <x-apply-modal :job="$job" />
                @elseif(Auth::user()->isCandidate() && $job->hasApplied(Auth::user()))
                <x-secondary-button disabled class="w-full justify-center py-3 cursor-not-allowed">
                    {{ __('Already Applied') }}
                </x-secondary-button>
                @endif
                @endauth

                @if ($job->url)
                <a href="{{ $job->url }}" target="_blank" rel="noopener noreferrer"
                    class="flex items-center justify-center gap-2 rounded-lg border px-4 py-2.5 text-sm font-semibold transition-colors duration-200
                            border-gray-200 bg-white text-gray-600 hover:text-gray-900 hover:border-gray-300 hover:bg-gray-50
                            dark:border-white/10 dark:bg-white/5 dark:text-gray-400 dark:hover:text-white dark:hover:border-white/20 dark:hover:bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    {{ __('Open Application Page') }}
                </a>
                @endif

                @auth
                @if(Auth::user()->isCandidate())
                <x-job-skill-match :job="$job" />
                @endif
                @endauth

            </aside>
        </div>
    </main>
</x-app-layout>
