@php
use App\Enums\ApplicationStatus;
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-xl leading-tight text-gray-900 dark:text-white">
                    {{ $application->candidate->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Applied for') }} · {{ $application->job->title }}
                </p>
            </div>

            <x-secondary-button type="button"
                onclick="window.location='{{ route('applications.index', $application->job) }}'">
                {{ __('Back to Applications') }}
            </x-secondary-button>
        </div>
    </x-slot>

    <main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[1fr_16rem]">

            <section class="space-y-6">

                {{-- candidate info --}}
                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <div class="flex items-center gap-4">
                        <x-profile-logo :user="$application->candidate" width="52" />
                        <div>
                            <p class="font-bold text-gray-950 dark:text-white">{{ $application->candidate->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ '@' .
                                $application->candidate->username }}</p>
                        </div>
                    </div>
                </div>

                {{-- cover letter --}}
                @if ($application->cover_letter)
                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('Cover Letter') }}
                    </h3>
                    <p class="whitespace-pre-line text-sm leading-7 text-gray-600 dark:text-gray-300">
                        {{ $application->cover_letter }}
                    </p>
                </div>
                @endif

            </section>

            <aside class="space-y-4">

                {{-- meta --}}
                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('Details') }}
                    </h3>

                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                            <dd class="mt-1">
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset {{ $application->status->color() }}">
                                    {{ $application->status->label() }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-gray-500 dark:text-gray-400">{{ __('Applied') }}</dt>
                            <dd class="mt-1 text-gray-900 dark:text-white">{{ $application->created_at->format('M d, Y')
                                }}</dd>
                        </div>
                    </dl>

                    {{-- resume --}}
                    <div class="mt-5 border-t border-gray-100 pt-5 dark:border-white/10">
                        <a href="{{ route('applications.resume', $application) }}">
                            <x-secondary-button class="w-full">
                                {{ __('Download Resume') }}
                            </x-secondary-button>
                        </a>
                    </div>
                </div>

                {{-- status update --}}
                @can('update', $application)
                @if (ApplicationStatus::updatable()->where(fn($s) =>
                $application->status->canTransitionTo($s))->isNotEmpty())
                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('Update Status') }}
                    </h3>

                    <div class="flex flex-col gap-2">
                        @foreach (ApplicationStatus::updatable() as $status)
                        @if ($application->status->canTransitionTo($status))
                        <form method="POST" action="{{ route('applications.update',  $application) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $status->value }}">
                            <button type="submit"
                                class="w-full cursor-pointer rounded-lg border px-4 py-2 text-xs font-semibold uppercase tracking-widest transition-all duration-150 active:scale-95 {{ $status->color() }}">
                                {{ __('Mark as') }} {{ $status->label() }}
                            </button>
                        </form>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
                @endcan

                {{-- status timeline --}}
                @if ($application->statusLogs->isNotEmpty())
                <div
                    class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                        {{ __('Status History') }}
                    </h3>

                    <ol class="relative border-l border-gray-200 dark:border-white/10 space-y-4 ml-2">
                        @foreach ($application->statusLogs as $log)
                        <li class="ml-4">
                            <div
                                class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full border border-white dark:border-gray-950 {{ str_contains($log->status->color(), 'green') ? 'bg-green-500' : 'bg-gray-400' }}">
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $log->status->color() }}">
                                    {{ $log->status->label() }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                {{ $log->created_at->format('M d, Y · H:i') }}
                            </p>
                        </li>
                        @endforeach
                    </ol>
                </div>
                @endif
            </aside>
        </div>
    </main>
</x-app-layout>
