@props(['skill', 'size' => 'base'])

@php
$classes = 'inline-flex items-center rounded-lg font-medium transition-colors duration-200
bg-blue-50 text-blue-700 border border-blue-100
hover:bg-blue-100 hover:border-blue-300
dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-500/20
dark:hover:bg-blue-500/20 dark:hover:text-blue-200';

if ($size === 'base') {
$classes .= ' px-4 py-1.5 text-sm';
}

if ($size === 'small') {
$classes .= ' px-2.5 py-1 text-xs';
}
@endphp

<span class="{{ $classes }}">{{ $skill->name }}</span>
