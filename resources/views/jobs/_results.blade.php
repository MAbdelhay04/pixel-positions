@if ($jobs->isEmpty())
<div
    class="rounded-lg border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
    <h2 class="text-xl font-bold text-gray-950 dark:text-white">No jobs found</h2>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600 dark:text-gray-400">
        @if(request()->hasAny(['q', 'type', 'location']))
        Try adjusting your search or filters.
        @else
        New opportunities will appear here as soon as they are published.
        @endif
    </p>
</div>
@else
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @foreach ($jobs as $job)
    <x-job-card :job="$job" />
    @endforeach
</div>

@if ($jobs->hasPages())
<div class="mt-8">
    {{ $jobs->links() }}
</div>
@endif
@endif
