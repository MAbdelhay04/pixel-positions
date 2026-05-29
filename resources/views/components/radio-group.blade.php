@props([
    'name' => '',
    'options' => [],
    'selected' => null,
])

<div {{ $attributes->merge(['class' => 'flex gap-3 flex-wrap']) }}>
    @foreach($options as $option)
        <label class="flex items-center gap-2.5 cursor-pointer group px-4 py-2.5 rounded-lg border transition-all duration-150
            border-gray-300 hover:border-gray-400 hover:bg-gray-50
            has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50
            dark:border-white/10 dark:hover:border-white/20 dark:hover:bg-white/5
            dark:has-[:checked]:border-blue-700 dark:has-[:checked]:bg-blue-900/20">
            <input type="radio" name="{{ $name }}" value="{{ $option['value'] }}"
                @checked(old($name, $selected) === $option['value'])
                class="w-3.5 h-3.5 text-blue-600 border-gray-300 focus:ring-blue-600 focus:ring-offset-white dark:border-white/20 dark:bg-transparent dark:focus:ring-blue-700 dark:focus:ring-offset-black">
            <span class="text-sm font-medium transition-colors duration-150 text-gray-600 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                {{ $option['label'] }}
            </span>
        </label>
    @endforeach
</div>
