@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 px-4 py-3 rounded-lg border border-green-700/40 bg-green-900/20 dark:border-green-800/50 dark:bg-green-900/20 text-sm text-green-600 dark:text-green-400 font-medium']) }}>
        <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
        {{ $status }}
    </div>
@endif
