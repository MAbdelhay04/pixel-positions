<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold leading-tight">{{ __('Company Settings') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your employer profile and brand details.') }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            @include('users.employer.settings-form')
        </div>
    </div>
</x-app-layout>
