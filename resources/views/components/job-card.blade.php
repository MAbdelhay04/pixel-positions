@props([
'job',
'withLink' => true
])

@php
$applicationsCount = $job->applications_count ?? $job->applications()->count();
@endphp

<x-panel class="group flex h-full flex-col transition-shadow duration-200 hover:shadow-md">
    <div class="flex items-start gap-3">
        <x-profile-logo :user="$job->employer" width="44" />

        <div class="min-w-0">
            <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $job->employer?->name ?? 'Hiring team' }}
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
        @foreach ($job->skills as $skill)
        <span
            class="rounded-lg bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/10 dark:text-gray-300">
            {{ $skill->name }}
        </span>
        @endforeach

        @foreach ($job->tags as $tag)
        <x-tag size="small" :$tag />
        @endforeach
    </div>
    @endif
</x-panel>
