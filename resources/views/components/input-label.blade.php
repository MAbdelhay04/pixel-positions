@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-semibold uppercase tracking-wider mb-1.5 text-gray-500 dark:text-gray-400']) }}>
    {{ $value ?? $slot }}
</label>
