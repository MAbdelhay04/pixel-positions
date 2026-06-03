@if ($notifications->isEmpty())
<div class="rounded-lg border border-gray-200 bg-white dark:border-white/10 dark:bg-white/5">
    @include('notifications._empty')
</div>
@else
<div id="notification-page-list" class="space-y-3">
    @foreach ($notifications as $notification)
    <x-notification-item :notification="$notification" />
    @endforeach
</div>

<div class="mt-6">
    {{ $notifications->links() }}
</div>
@endif
