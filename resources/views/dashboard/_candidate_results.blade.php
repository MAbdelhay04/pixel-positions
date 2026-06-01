@if ($applications->isEmpty())
<section
    class="rounded-lg border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
    @if(request()->hasAny(['q', 'status', 'type', 'location']))
    <h3 class="text-xl font-bold text-gray-950 dark:text-white">{{ __('No applications match your filters') }}</h3>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600 dark:text-gray-400">
        {{ __('Try adjusting or clearing your filters.') }}
    </p>
    <a href="{{ route('dashboard') }}"
        class="mt-6 inline-block text-sm font-medium text-blue-600 underline underline-offset-2 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
        {{ __('Clear all filters') }}
    </a>
    @else
    <h3 class="text-xl font-bold text-gray-950 dark:text-white">{{ __('No applications yet') }}</h3>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600 dark:text-gray-400">
        {{ __('Start browsing jobs and submit your first application.') }}
    </p>
    <x-primary-button type="button" onclick="window.location='{{ route('jobs.index') }}'" class="mt-6">
        {{ __('Browse Jobs') }}
    </x-primary-button>
    @endif
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
