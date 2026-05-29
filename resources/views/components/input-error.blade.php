@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-1.5 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="text-xs text-red-500 dark:text-red-400 flex items-center gap-1.5">
                <span class="w-1 h-1 rounded-full bg-current inline-block shrink-0"></span>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
