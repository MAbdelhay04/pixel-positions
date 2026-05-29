<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage open, draft, and closed job listings.
                </p>
            </div>

            <x-primary-button type="button" onclick="window.location='{{ route('jobs.create') }}'">
                {{ __('Post a New Job') }}
            </x-primary-button>
    </x-slot>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <section class="mb-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['total'] }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Open') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['open'] }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Applications') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['applications'] }}</p>
            </div>
        </section>

        @if ($jobs->isEmpty())
        <section
            class="rounded-lg border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
            <h3 class="text-xl font-bold text-gray-950 dark:text-white">{{ __('No jobs yet') }}</h3>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600 dark:text-gray-400">
                {{ __('Create your first listing to start collecting applications.') }}
            </p>
            <x-primary-button type="button" onclick="window.location='{{ route('jobs.create') }}'" class="mt-6">
                {{ __('Create Job') }}
            </x-primary-button>
        </section>
        @else
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($jobs as $job)
            <x-job-card :job="$job" />
            @endforeach
        </section>

        @if ($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
        @endif
        @endif

    </main>

</x-app-layout>
