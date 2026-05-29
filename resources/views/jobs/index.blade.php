<x-app-layout>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <section
            class="flex flex-col gap-6 border-b border-gray-200 pb-10 dark:border-white/10 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-blue-700 dark:text-blue-400">
                    Open roles
                </p>
                <h1 class="mt-3 text-4xl font-bold tracking-normal text-gray-950 dark:text-white sm:text-5xl">
                    Find your next role
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-gray-600 dark:text-gray-400">
                    Browse current job listings from teams hiring through {{ config('app.name', 'Laravel') }}.
                </p>
            </div>

            <div
                class="rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-600 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-gray-300">
                <span class="font-semibold text-gray-950 dark:text-white">{{ $jobs->count() }}</span>
                {{ \Illuminate\Support\Str::plural('open job', $jobs->count()) }}
            </div>
        </section>

        <section class="py-10">
            @if ($jobs->isEmpty())
            <div
                class="rounded-lg border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
                <h2 class="text-xl font-bold text-gray-950 dark:text-white">No open jobs posted yet</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600 dark:text-gray-400">
                    New opportunities will appear here as soon as they are published.
                </p>
            </div>
            @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($jobs as $job)
                <x-job-card :job="$job" />
                @endforeach
            </div>

            @endif
        </section>
        @if ($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
        @endif
    </main>
</x-app-layout>
