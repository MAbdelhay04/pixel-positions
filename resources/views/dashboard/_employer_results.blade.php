@if ($jobs->isEmpty())
<section
    class="rounded-lg border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
    <h3 class="text-xl font-bold text-gray-950 dark:text-white">
        {{ request()->hasAny(['q', 'type', 'location', 'status']) ? __('No jobs match your filters') : __('No jobs yet') }}
    </h3>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600 dark:text-gray-400">
        @if(request()->hasAny(['q', 'type', 'location', 'status']))
        {{ __('Try adjusting your search or filters.') }}
        @else
        {{ __('Create your first listing to start collecting applications.') }}
        @endif
    </p>
    @if(request()->hasAny(['q', 'type', 'location', 'status']))
    <a href="{{ route('dashboard') }}"
        class="mt-4 inline-block text-sm text-blue-700 underline underline-offset-2 dark:text-blue-400">
        {{ __('Clear all filters') }}
    </a>
    @else
    <x-primary-button type="button" onclick="window.location='{{ route('jobs.create') }}'" class="mt-6">
        {{ __('Create Job') }}
    </x-primary-button>
    @endif
</section>
@else
<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @foreach ($jobs as $job)
    <x-job-card :job="$job" :withStatusUpdate="true" />
    @endforeach
</section>

@if ($jobs->hasPages())
<div class="mt-8">
    {{ $jobs->links() }}
</div>
@endif
@endif
