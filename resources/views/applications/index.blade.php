<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-xl leading-tight text-gray-900 dark:text-white">
                    {{ __('Applications') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $job->title }}
                </p>
            </div>

            <x-secondary-button type="button" onclick="window.location='{{ route('jobs.show', $job) }}'">
                {{ __('Back to Job') }}
            </x-secondary-button>
        </div>
    </x-slot>

    <main
        class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8"
        x-data="ajaxSearch({
            url: '{{ route('applications.index', $job) }}',
            resultsSelector: '#applications-results'
        })">

        {{-- Stats — static, not replaced by AJAX --}}
        <section class="mb-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['total'] }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Reviewing') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['reviewing'] }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Interviews') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['interviews'] }}</p>
            </div>
        </section>

        {{-- Search --}}
        <div class="mb-6">
            <x-application-index-search :job="$job" />
        </div>

        {{-- Loading spinner --}}
        <div x-show="loading" x-cloak class="flex items-center justify-center py-12">
            <svg class="h-6 w-6 animate-spin text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
        </div>

        {{-- Fetch error --}}
        <div x-show="fetchError && searchAttempted" x-cloak
            class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
            {{ __('Something went wrong. Please try again or refresh the page.') }}
        </div>

        {{-- Results (replaced on each AJAX call) --}}
        <div id="applications-results" x-show="!loading">
            @include('applications._results')
        </div>

    </main>
</x-app-layout>
