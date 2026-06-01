<x-app-layout>

    <main
        class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8"
        x-data="ajaxSearch({
            url: '{{ ($tag ?? false) ? route('jobs.index_tag', $tag) : route('jobs.index') }}',
            resultsSelector: '#jobs-results'
        })">

        <section
            class="flex flex-col gap-6 border-b border-gray-200 pb-10 dark:border-white/10 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">
                    Open roles
                </p>
                <h1 class="mt-3 text-4xl font-bold tracking-normal text-gray-950 dark:text-white sm:text-5xl">
                    @if($tag ?? false)
                    Open Jobs tagged "{{ $tag->name }}"
                    @else
                    Find your next role
                    @endif
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-gray-600 dark:text-gray-400">
                    @if($tag ?? false)
                    Showing jobs tagged with <span class="font-semibold text-gray-950 dark:text-white">{{ $tag->name }}</span>.
                    <a href="{{ route('jobs.index') }}" class="text-blue-700 underline dark:text-blue-400">View all jobs</a>
                    @else
                    Browse current job listings from teams hiring through {{ config('app.name', 'Laravel') }}.
                    @endif
                </p>
            </div>

            <div
                class="rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-600 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-gray-300">
                <span class="font-semibold text-gray-950 dark:text-white">{{ $jobs->total() }}</span>
                {{ Str::plural('open job', $jobs->total()) }}
            </div>
        </section>

        {{-- Search & Filters --}}
        <div class="mt-6">
            <x-job-search :action="($tag ?? false) ? route('jobs.index_tag', $tag) : route('jobs.index')" />
        </div>

        {{-- Loading spinner --}}
        <div x-show="loading" x-cloak class="flex items-center justify-center py-12">
            <svg class="h-6 w-6 animate-spin text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
        </div>

        {{-- Fetch error --}}
        <div x-show="fetchError && searchAttempted" x-cloak
            class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
            {{ __('Something went wrong. Please try again or refresh the page.') }}
        </div>

        {{-- Results --}}
        <section id="jobs-results" class="py-10" x-show="!loading">
            @include('jobs._results')
        </section>

        {{-- Pagination (inside results partial when AJAX, here for initial load) --}}

    </main>
</x-app-layout>
