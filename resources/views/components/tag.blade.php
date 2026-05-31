@props(['tag', 'size' => 'base'])

@php
$classes = 'inline-flex items-center rounded-lg border font-semibold transition-colors duration-200 bg-white
text-gray-700 border-gray-200 hover:border-blue-300 hover:text-blue-700 dark:bg-white/10 dark:text-gray-200
dark:border-white/10 dark:hover:bg-white/20 dark:hover:text-white';

if ($size === 'base') {
$classes .= ' px-4 py-1.5 text-sm';
}

if ($size === 'small') {
$classes .= ' px-2.5 py-1 text-xs';
}
@endphp

<a href="{{ route('jobs.index_tag',$tag) }}" class="{{ $classes }}">{{ $tag->name }}</a>
