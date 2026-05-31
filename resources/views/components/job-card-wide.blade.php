@props([
'job',
'withLink' => true,
'withApply' => true,
])

@php
$applicationsCount = $job->applications_count ?? $job->applications()->count();
@endphp

<x-panel class="group flex flex-col gap-4 transition-shadow duration-200 hover:shadow-md">

    {{-- top row --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:gap-6">
        <div class="shrink-0">
            <x-profile-logo :user="$job->employer" width="48" />
        </div>

        <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $job->employer?->name ?? 'Hiring team' }}
            </p>

            <h3
                class="mt-1 text-xl font-bold text-gray-900 transition-colors duration-200 group-hover:text-indigo-700 dark:text-white dark:group-hover:text-indigo-400">
                @if ($withLink)
                <a href="{{ route('jobs.show', $job) }}">
                    {{ $job->title }}
                </a>
                @else
                {{ $job->title }}
                @endif
            </h3>

            @if ($job->category)
            <p class="mt-1 text-xs font-medium text-gray-400 dark:text-gray-500">
                {{ $job->category->name }}
            </p>
            @endif

            <div class="mt-3 flex flex-wrap items-center gap-2">
                <span
                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $job->location->color() }}">
                    {{ $job->location->label() }}
                </span>

                <span
                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $job->type->color() }}">
                    {{ $job->type->label() }}
                </span>

                <span
                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $job->status->color() }}">
                    {{ $job->status->label() }}
                </span>

                @if ($job->salary_range)
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $job->salary_range }}</span>
                @endif
            </div>

            @if ($job->skills->isNotEmpty())
            <div class="mt-3">
                <span class="text-xs text-gray-400 dark:text-gray-500">Skills:</span>
                <div class="mt-1 flex flex-wrap gap-1.5">
                    @foreach ($job->skills as $skill)
                    <x-skill :$skill size="small" />
                    @endforeach
                </div>
            </div>
            @endif

            <p class="mt-3 text-xs text-gray-400 dark:text-gray-500">
                {{ $applicationsCount }} {{ Str::plural('applicant', $applicationsCount) }}
                - Posted {{ $job->created_at?->diffForHumans() }}
            </p>
        </div>

        @if ($job->tags->isNotEmpty())
        <div class="flex shrink-0 flex-col gap-2 sm:max-w-52 sm:items-end">
            <span class="text-xs text-gray-400 dark:text-gray-500">Tags:</span>
            <div class="flex flex-wrap gap-2 sm:justify-end">
                @foreach ($job->tags as $tag)
                <x-tag :$tag />
                @endforeach
            </div>
        </div>
        @endif
    </div>

    @if ($withApply)
    @can('apply', $job)
    <div class="border-t border-gray-100 pt-4 dark:border-white/10">
        <x-primary-button x-data="" x-on:click="$dispatch('open-modal', 'apply-{{ $job->id }}')" class="w-full
            sm:w-auto">
            {{ __('Apply') }}
        </x-primary-button>
    </div>

    <x-apply-modal :job="$job" />
    @else
    <div class="border-t border-gray-100 pt-4 dark:border-white/10">
        <x-secondary-button disabled class="mt-3 w-full">
            {{ __('Already Applied') }}
        </x-secondary-button>
    </div>
    @endcan
    @endif


</x-panel>
