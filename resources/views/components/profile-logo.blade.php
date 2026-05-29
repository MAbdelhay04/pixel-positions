@props([
'width' => 40,
'user' => Auth::user(),
])

@php
$size = is_numeric($width) ? "{$width}px" : $width;
$initials = \Illuminate\Support\Str::of($user?->name ?? config('app.name'))->trim()->substr(0, 1)->upper();
@endphp

@if ($user?->logo)
<img {{ $attributes->merge(['class' => 'shrink-0 rounded-lg object-cover']) }}
src="{{ asset('storage/' . $user->logo) }}"
width="{{ $width }}"
height="{{ $width }}"
style="width: {{ $size }}; height: {{ $size }};"
alt="{{ $user->name }}">
@else
<span {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center justify-center rounded-lg border
    border-gray-200 bg-gray-100 font-bold text-gray-700 dark:border-white/10 dark:bg-white/10 dark:text-white']) }}
    style="width: {{ $size }}; height: {{ $size }};">
    {{ $initials }}
</span>
@endif
