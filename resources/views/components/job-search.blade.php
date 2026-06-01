@props([
'action' => null,
'showStatus' => false,
])

@php
use App\Enums\JobLocation;
use App\Enums\JobStatus;
use App\Enums\JobType;

$action = $action ?? route('jobs.index');

$typeOptions = collect(JobType::cases())
->map(fn($t) => ['value' => $t->value, 'label' => $t->label()])
->values()
->all();

$locationOptions = collect(JobLocation::cases())
->map(fn($l) => ['value' => $l->value, 'label' => $l->label()])
->values()
->all();

$statusOptions = collect(JobStatus::cases())
->map(fn($s) => ['value' => $s->value, 'label' => $s->label()])
->values()
->all();

$hasFilters = request()->hasAny(['type', 'location', 'status']);

$activeFilterCount = collect(['type', 'location', 'status'])
->filter(fn($k) => request()->filled($k))
->count();
@endphp

<form method="GET" action="{{ $action }}"
    class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5"
    x-data="{ filtersOpen: {{ $hasFilters ? 'true' : 'false' }} }">

    {{-- Search bar row --}}
    <div class="flex items-center gap-3 px-5 py-3.5">

        <svg class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
        </svg>


        <x-text-input type="search" name="q" :value="request('q')"
            placeholder="{{ __('Search job titles, companies…') }}" autocomplete="off"
            class="min-w-0 flex-1 !border-transparent !bg-transparent !shadow-none !ring-0 focus:!border-transparent focus:!ring-0" />

        <x-secondary-button type="button" x-on:click="filtersOpen = !filtersOpen" class="shrink-0 gap-2">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4h18M7 8h10M11 12h2M9 16h6" />
            </svg>
            {{ __('Filters') }}
            @if($activeFilterCount > 0)
            <span
                class="flex h-4 w-4 items-center justify-center rounded-full bg-blue-600 text-[10px] font-bold text-white dark:bg-blue-500">
                {{ $activeFilterCount }}
            </span>
            @endif
        </x-secondary-button>

        <x-primary-button type="submit">
            {{ __('Search') }}
        </x-primary-button>
    </div>

    {{-- Filter panel --}}
    <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="border-t border-gray-100 px-5 py-5 dark:border-white/10"
        style="display: {{ $hasFilters ? 'block' : 'none' }}">

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Job Type --}}
            <div>
                <x-input-label :value="__('Job Type')" />
                <x-multi-select name="type" :options="$typeOptions" :selected="(array) request('type', [])"
                    placeholder="{{ __('All types') }}" />
            </div>

            {{-- Location --}}
            <div>
                <x-input-label :value="__('Location')" />
                <x-multi-select name="location" :options="$locationOptions" :selected="(array) request('location', [])"
                    placeholder="{{ __('All locations') }}" />
            </div>

            @if($showStatus)
            {{-- Status --}}
            <div>
                <x-input-label :value="__('Status')" />
                <x-multi-select name="status" :options="$statusOptions" :selected="(array) request('status', [])"
                    placeholder="{{ __('All statuses') }}" />
            </div>
            @endif

        </div>

        @if(request()->hasAny(['q', 'type', 'location', 'status']))
        <div class="mt-5 flex justify-end border-t border-gray-100 pt-4 dark:border-white/10">
            <a href="{{ $action }}"
                class="text-xs font-medium text-gray-400 underline underline-offset-2 transition-colors duration-150 hover:text-gray-700 dark:hover:text-gray-200">
                {{ __('Clear all filters') }}
            </a>
        </div>
        @endif

    </div>
</form>
