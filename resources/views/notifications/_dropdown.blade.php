@if ($notifications->isEmpty())
@include('notifications._empty')
@else
<div id="notification-dropdown-list">
    @foreach ($notifications as $notification)
    <x-notification-item :notification="$notification" compact />
    @endforeach
</div>
@endif
