<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-gray-900 dark:text-white">
                    {{ $job->title }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $job->employer?->name ?? 'Hiring team' }}
                </p>
            </div>

            <div class="flex gap-3">
                <x-secondary-button type="button" onclick="window.location='{{ route('jobs.index') }}'">
                    {{ __('Back') }}
                </x-secondary-button>
                @if ($job->employer->id === Auth::user()->id)
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
                @endif

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

            <aside
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
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $job->status->label() }}</dd>
                    </div>
                    @if ($job->salary_range)
                    <div>
                        <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('Salary') }}</dt>
                        <dd class="mt-1 text-gray-900 dark:text-white">{{ $job->salary_range }}</dd>
                    </div>
                    @endif
                </dl>
                @if ($job->url)
                <a href="{{ $job->url }}" target="_blank" rel="noopener noreferrer" class="mt-3 w-full">
                    <x-primary-button class="w-full mt-3">
                        {{ __('Open Application') }}
                    </x-primary-button>
                </a>
                @endif
                @can('apply', $job)
                <x-primary-button x-data="" x-on:click="$dispatch('open-modal', 'apply-{{ $job->id }}')"
                    class="mt-3 w-full">
                    {{ __('Apply Now') }}
                </x-primary-button>
                <x-apply-modal :job="$job" />
                @else
                <x-secondary-button disabled class="mt-3 w-full">
                    {{ __('Already Applied') }}
                </x-secondary-button>
                @endcan
            </aside>
        </div>
    </main>
</x-app-layout>
