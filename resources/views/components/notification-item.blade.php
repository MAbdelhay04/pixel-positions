@props(['notification', 'compact' => false])

@include(notification_type_view($notification), [
'notification' => $notification,
'compact' => $compact,
])
