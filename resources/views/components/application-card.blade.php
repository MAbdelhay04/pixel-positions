@props(['application'])

<div
    class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <a href="{{ route('jobs.show', $application->job) }}"
                class="font-semibold text-gray-950 hover:text-indigo-700 dark:text-white dark:hover:text-indigo-400 transition-colors duration-150">
                {{ $application->job->title }}
            </a>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $application->job->employer->name }}
            </p>
        </div>

        <span
            class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $application->job->location->color() }}">
            {{ $application->job->location->label() }}
        </span>
    </div>

    <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-white/10">
        <span
            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $application->status->color() }}">
            {{ $application->status->label() }}
        </span>

        <span class="text-xs text-gray-400 dark:text-gray-500">
            {{ $application->created_at->format('M d, Y') }}
        </span>
    </div>
</div>
