@props([
    'job',
    'compact' => false,
])

@php
    use App\Enums\JobStatus;

    $transitions = JobStatus::updatable()->filter(
        fn (JobStatus $status) => $job->status->canTransitionTo($status),
    );
@endphp

@can('update', $job)
    @if ($transitions->isNotEmpty())
        <div @class([
            'rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5' => ! $compact,
            'mt-4 border-t border-gray-100 pt-4 dark:border-white/10' => $compact,
        ])>
            @unless ($compact)
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                    {{ __('Update Status') }}
                </h3>
            @else
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    {{ __('Update Status') }}
                </p>
            @endunless

            <div class="flex flex-col gap-2">
                @foreach ($transitions as $status)
                    <form method="POST" action="{{ route('jobs.update_status', $job) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status->value }}">
                        <button type="submit"
                            class="w-full cursor-pointer rounded-lg border px-4 py-2 text-xs font-semibold uppercase tracking-widest transition-all duration-150 active:scale-95 {{ $status->color() }}">
                            {{ __('Mark as') }} {{ $status->label() }}
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    @endif
@endcan
