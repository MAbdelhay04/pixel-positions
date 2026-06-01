@if ($applications->isEmpty())
<section
    class="rounded-lg border border-dashed border-gray-300 bg-white px-6 py-12 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
    <h3 class="text-xl font-bold text-gray-950 dark:text-white">
        @if(request()->hasAny(['q', 'status', 'date_from', 'date_to']))
            {{ __('No applications match your filters') }}
        @else
            {{ __('No applications yet') }}
        @endif
    </h3>
    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-600 dark:text-gray-400">
        @if(request()->hasAny(['q', 'status', 'date_from', 'date_to']))
            {{ __('Try adjusting or clearing your filters.') }}
        @else
            {{ __('Applications will appear here once candidates start applying.') }}
        @endif
    </p>
</section>
@else
<section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    @foreach ($applications as $application)
    <x-application-card-employer :$application />
    @endforeach
</section>

@if ($applications->hasPages())
<div class="mt-8">
    {{ $applications->links() }}
</div>
@endif
@endif
