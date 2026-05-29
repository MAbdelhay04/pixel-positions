@props([
'job',
'withLink' => true
])

@php
$applicationsCount = $job->applications_count ?? $job->applications()->count();
@endphp

<x-panel
    class="group flex flex-col gap-4 transition-shadow duration-200 hover:shadow-md sm:flex-row sm:items-start sm:gap-6">
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
        <div class="mt-3 flex flex-wrap gap-1.5">
            @foreach ($job->skills as $skill)
            <span
                class="rounded-lg bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/10 dark:text-gray-300">
                {{ $skill->name }}
            </span>
            @endforeach
        </div>
        @endif

        <p class="mt-3 text-xs text-gray-400 dark:text-gray-500">
            {{ $applicationsCount }} {{ \Illuminate\Support\Str::plural('applicant', $applicationsCount) }}
            - Posted {{ $job->created_at?->diffForHumans() }}
        </p>
    </div>

    @if ($job->tags->isNotEmpty())
    <div class="flex shrink-0 flex-wrap gap-2 sm:max-w-52 sm:justify-end">
        @foreach ($job->tags as $tag)
        <x-tag :$tag />
        @endforeach
    </div>
    @endif
</x-panel>
