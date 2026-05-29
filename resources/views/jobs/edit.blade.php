<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-gray-900 dark:text-white">
                    {{ __('Edit Job') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update the listing details, status, skills, and tags.
                </p>
            </div>

            <div class="flex gap-3">
                <x-secondary-button type="button" onclick="window.location='{{ route('jobs.index') }}'">
                    {{ __('Back') }}
                </x-secondary-button>
                @if ($job->employer->id === Auth::user()->id)
                <form method="POST" action="{{ route('jobs.destroy', $job) }}"
                    onsubmit="return confirm('{{ __('Are you sure you want to delete this job?') }}')">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>
                        {{ __('Delete Job') }}
                    </x-danger-button>
                </form>
                @endif
            </div>
        </div>
    </x-slot>

    <main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        @include('jobs._form', [
        'action' => route('jobs.update', $job),
        'method' => 'PUT',
        'submitLabel' => __('Save Changes'),
        'job' => $job,
        ])
    </main>
</x-app-layout>
