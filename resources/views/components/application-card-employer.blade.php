@props(['application'])

<div
    class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">

    <div class="flex items-start justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <x-profile-logo :user="$application->candidate" width="40" />
            <div class="min-w-0">
                <a href="{{ route('applications.show', $application) }}"
                    class="font-semibold text-gray-950 hover:text-indigo-700 dark:text-white dark:hover:text-indigo-400 transition-colors duration-150">
                    {{ $application->candidate->name }}
                </a>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $application->candidate->username }}
                </p>
            </div>
        </div>

        <span
            class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $application->status->color() }}">
            {{ $application->status->label() }}
        </span>
    </div>

    @if ($application->cover_letter)
    <p
        class="line-clamp-3 text-sm leading-6 text-gray-600 dark:text-gray-400 border-t border-gray-100 pt-3 dark:border-white/10">
        {{ $application->cover_letter }}
    </p>
    @endif

    <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-white/10">
        <a href="{{ route('applications.resume', $application) }}"
            class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-700 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            {{ __('Download Resume') }}
        </a>

        <span class="text-xs text-gray-400 dark:text-gray-500">
            {{ $application->created_at->format('M d, Y') }}
        </span>
    </div>
</div>
