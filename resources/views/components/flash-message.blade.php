@if (session('success'))
<div
    class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div
    class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
    {{ session('error') }}
</div>
@endif
