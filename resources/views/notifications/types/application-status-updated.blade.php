@props(['notification', 'compact' => false])

@php
use App\Enums\ApplicationStatus;

$data = $notification->data;
$statusValue = $data['status_value'] ?? null;
$statusEnum = $statusValue ? ApplicationStatus::tryFrom($statusValue) : null;
$statusLabel = $data['status'] ?? '';
$message = $data['message'] ?? __('Your application for :job is now :status.', [
'job' => $data['job_title'] ?? '',
'status' => $statusLabel,
]);
$url = notification_redirect_url($notification);
$isUnread = is_null($notification->read_at);
@endphp

<x-notification-list-item :notification="$notification" :compact="$compact" :url="$url">
    <p @class([ 'text-sm leading-snug' , 'font-semibold text-gray-900 dark:text-white'=> $isUnread,
        'text-gray-700 dark:text-gray-300' => ! $isUnread,
        ])>
        {{ $message }}
    </p>

    @if ($statusEnum)
    <span
        class="mt-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusEnum->color() }}">
        {{ $statusEnum->label() }}
    </span>
    @elseif ($statusLabel)
    <span
        class="mt-2 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-white/10 dark:text-gray-300">
        {{ $statusLabel }}
    </span>
    @endif
</x-notification-list-item>
