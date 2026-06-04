@props([
    'job',
    'withLink' => true,
    'withStatusUpdate' => false,
])

@php
$applicationsCount = $job->applications_count ?? $job->applications()->count();
@endphp

<x-panel class="group flex h-full flex-col transition-shadow duration-200 hover:shadow-md">
    <div class="flex items-start gap-3">
        @if ($job->employer)
        <a href="{{ route('companies.show', $job->employer) }}" class="shrink-0">
            <x-profile-logo :user="$job->employer" width="44" />
        </a>
        @else
        <x-profile-logo :user="$job->employer" width="44" />
        @endif

        <div class="min-w-0">
            <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                @if ($job->employer)
                <a href="{{ route('companies.show', $job->employer) }}" class="transition-colors hover:text-indigo-700 dark:hover:text-indigo-400">
                    {{ $job->employer->name }}
                </a>
                @else
                {{ __('Hiring team') }}
                @endif
            </p>

            @if ($job->category)
            <p class="mt-0.5 truncate text-xs font-medium text-gray-400 dark:text-gray-500">
                {{ $job->category->name }}
            </p>
            @endif
        </div>
    </div>

    <div class="mt-5 flex-1">
        <h3
            class="text-xl font-bold leading-snug text-gray-900 transition-colors duration-200 group-hover:text-indigo-700 dark:text-white dark:group-hover:text-indigo-400">
            @if ($withLink)
            <a href="{{ route('jobs.show', $job) }}">
                {{ $job->title }}
            </a>
            @else
            {{ $job->title }}
            @endif
        </h3>

        <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600 dark:text-gray-400">
            {{ $job->description }}
        </p>

        <div class="mt-4 flex flex-wrap gap-2">
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
        </div>

        @if ($job->salary_range)
        <p class="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ $job->salary_range }}
        </p>
        @endif

        <p class="mt-3 text-xs text-gray-400 dark:text-gray-500">
            {{ $applicationsCount }} {{ \Illuminate\Support\Str::plural('applicant', $applicationsCount) }}
        </p>
    </div>

    @if ($job->skills->isNotEmpty() || $job->tags->isNotEmpty())
    <div class="mt-5 flex flex-wrap gap-2 border-t border-gray-100 pt-5 dark:border-white/10">
        @if($job->skills->isNotEmpty())
        <span class="text-xs text-gray-400 dark:text-gray-500">Skills:</span>
        @foreach ($job->skills as $skill)
        <x-skill size="small" :$skill />
        @endforeach
        @endif

        @if($job->tags->isNotEmpty())
        <span class="text-xs text-gray-400 dark:text-gray-500">Tags:</span>
        @foreach ($job->tags as $tag)
        <x-tag size="small" :$tag />
        @endforeach
        @endif
    </div>
    @endif

    @auth
    @can('apply', $job)
    <div class="mt-4 border-t border-gray-100 pt-4 dark:border-white/10">
        <x-primary-button x-data="" x-on:click="$dispatch('open-modal', 'apply-{{ $job->id }}')" class="w-full">
            {{ __('Apply') }}
        </x-primary-button>
    </div>
    <x-apply-modal :job="$job" />
    @elseif ($job->hasApplied(Auth::user()))
    <div class="mt-4 border-t border-gray-100 pt-4 dark:border-white/10">
        <x-secondary-button disabled class="mt-3 w-full">
            {{ __('Already Applied') }}
        </x-secondary-button>
    </div>
    @endif
    @endauth

    @if ($withStatusUpdate)
        <x-job-status-update :job="$job" compact />
    @endif

</x-panel>
