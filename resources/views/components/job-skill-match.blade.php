@php
$userSkills = Auth::user()->skills->pluck('name')->map(fn($s) => strtolower(trim($s)))->toArray();
$jobSkills = $job->skills->pluck('name')->map(fn($s) => strtolower(trim($s)))->toArray();
$score = skillsMatchScore($userSkills, $jobSkills);
$matched = array_values(array_intersect($jobSkills, $userSkills));
$missing = array_values(array_diff($jobSkills, $userSkills));

$barColor = match(true) {
$score >= 75 => 'bg-emerald-500',
$score >= 40 => 'bg-yellow-400',
default => 'bg-red-500',
};

$label = match(true) {
$score >= 75 => 'Strong match',
$score >= 40 => 'Partial match',
default => 'Low match',
};

$labelColor = match(true) {
$score >= 75 => 'text-emerald-600 dark:text-emerald-400',
$score >= 40 => 'text-yellow-600 dark:text-yellow-400',
default => 'text-red-600 dark:text-red-400',
};
@endphp

@if(count($jobSkills) > 0)
<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">

    <div class="flex items-center justify-between">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">
            {{ __('Skills Match') }}
        </h3>
        <span class="text-sm font-semibold {{ $labelColor }}">{{ $label }}</span>
    </div>

    {{-- Progress bar --}}
    <div class="mt-4">
        <div class="mb-1.5 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <span>{{ count($matched) }} / {{ count($jobSkills) }} skills matched</span>
            <span class="font-semibold {{ $labelColor }}">{{ $score }}%</span>
        </div>
        <div class="h-2.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-white/10">
            <div class="h-2.5 rounded-full transition-all duration-500 {{ $barColor }}" style="width: {{ $score }}%">
            </div>
        </div>
    </div>

    {{-- Matched skills --}}
    @if(count($matched) > 0)
    <div class="mt-5">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
            {{ __('You have') }}
        </p>
        <div class="flex flex-wrap gap-1.5">
            @foreach($matched as $skill)
            <span
                class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/20">
                <svg class="h-3 w-3 shrink-0" viewBox="0 0 12 12" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414L4.586 7.586l4.293-4.293a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
                {{ $skill }}
            </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Missing skills --}}
    @if(count($missing) > 0)
    <div class="mt-4">
        <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
            {{ __("You're missing") }}
        </p>
        <div class="flex flex-wrap gap-1.5">
            @foreach($missing as $skill)
            <span
                class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500 ring-1 ring-inset ring-gray-300 dark:bg-white/5 dark:text-gray-400 dark:ring-white/10">
                <svg class="h-3 w-3 shrink-0" viewBox="0 0 12 12" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M6 1a5 5 0 100 10A5 5 0 006 1zm-.75 2.75a.75.75 0 011.5 0v3a.75.75 0 01-1.5 0v-3zm.75 5.5a.875.875 0 100-1.75.875.875 0 000 1.75z"
                        clip-rule="evenodd" />
                </svg>
                {{ $skill }}
            </span>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endif
