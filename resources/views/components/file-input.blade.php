@props([
'disabled' => false,
'accept' => null,
])

<input type="file" @disabled($disabled) @if($accept) accept="{{ $accept }}" @endif {{ $attributes->merge([
'class' => 'block w-full text-sm
text-gray-500 dark:text-gray-400
file:mr-4 file:py-2 file:px-4
file:rounded-md file:border-0
file:text-sm file:font-medium
file:bg-indigo-50 file:text-indigo-700
dark:file:bg-indigo-900/30 dark:file:text-indigo-300
hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50
focus:outline-none
rounded-md border border-gray-300
dark:border-gray-700 dark:bg-gray-900
cursor-pointer'
]) }}
>
