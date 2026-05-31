@props([
'width' => 40,
'user' => Auth::user(),
])

@php
$size = is_numeric($width) ? "{$width}px" : $width;

$userName = $user?->name ?? config('app.name');
$initials = Str::of($userName)->trim()->substr(0, 1)->upper();

$logoUrl = $user?->logo
? Storage::url($user->logo)
: null;

@endphp

@if ($logoUrl)
<img {{ $attributes->merge(['class' => 'shrink-0 rounded-lg object-cover']) }}
src="{{ $logoUrl }}" onerror="this.remove()"
width="{{ $width }}"
height="{{ $width }}"
style="width: {{ $size }}; height: {{ $size }};"
alt="{{ $userName }}"
>
@else
<span {{ $attributes->merge([
    'class' => 'inline-flex shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-100 font-bold
    text-gray-700 dark:border-white/10 dark:bg-white/10 dark:text-white'
    ]) }}
    style="width: {{ $size }}; height: {{ $size }};"
    >
    {{ $initials }}
</span>
@endif
