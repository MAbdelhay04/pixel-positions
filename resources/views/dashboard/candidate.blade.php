<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-xl leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Track your job applications and their status.') }}
                </p>
            </div>

            <x-primary-button type="button" onclick="window.location='{{ route('jobs.index') }}'">
                {{ __('Browse Jobs') }}
            </x-primary-button>
        </div>
    </x-slot>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <section class="mb-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Applied') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['total'] }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Pending') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['pending'] }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Interviews') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">{{ $stats['interviews'] }}</p>
            </div>
        </section>

        @if ($applications->isEmpty())
        <section
            class="rounded-lg border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
            <h3 class="text-xl font-bold text-gray-950 dark:text-white">{{ __('No applications yet') }}</h3>
            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600 dark:text-gray-400">
                {{ __('Start browsing jobs and submit your first application.') }}
            </p>
            <x-primary-button type="button" onclick="window.location='{{ route('jobs.index') }}'" class="mt-6">
                {{ __('Browse Jobs') }}
            </x-primary-button>
        </section>
        @else
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($applications as $application)
            <x-application-card :$application />
            @endforeach
        </section>

        @if ($applications->hasPages())
        <div class="mt-8">
            {{ $applications->links() }}
        </div>
        @endif
        @endif

    </main>
</x-app-layout>
