<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold leading-tight text-gray-900 dark:text-white">
                {{ __('Create Job') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Publish a role with clear details, matching skills, and searchable tags.
            </p>
        </div>
    </x-slot>

    <main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        @include('jobs._form', [
        'action' => route('jobs.store'),
        'submitLabel' => __('Create Job'),
        ])
    </main>
</x-app-layout>
