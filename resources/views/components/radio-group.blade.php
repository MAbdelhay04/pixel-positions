@props([
'name' => '',
'options' => [], // ['value' => 'employer', 'label' => 'Employer']
'selected' => null,
])

<div {{ $attributes->merge(['class' => 'flex gap-4 flex-wrap']) }}>
    @foreach($options as $option)
    <label class="flex items-center gap-2 cursor-pointer group">
        <input type="radio" name="{{ $name }}" value="{{ $option['value'] }}" @checked(old($name,
            $selected)===$option['value'])
            class="w-4 h-4 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:checked:bg-indigo-600">
        <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-100">
            {{ $option['label'] }}
        </span>
    </label>
    @endforeach
</div>
