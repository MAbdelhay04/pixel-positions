@props([
'name' => '',
'options' => [], // ['value' => 1, 'label' => 'PHP']
'selected' => [],
'placeholder' => 'Search...',
])

<div x-data="{
        open: false,
        search: '',
        selected: @js(old($name . '[]', $selected) ?? []),
        options: @js($options),
        get filtered() {
            return this.options.filter(o =>
                o.label.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        toggle(val) {
            const idx = this.selected.indexOf(val);
            idx === -1 ? this.selected.push(val) : this.selected.splice(idx, 1);
        },
        isSelected(val) {
            return this.selected.includes(val);
        }
    }" x-on:click.outside="open = false" class="relative">
    {{-- Hidden inputs --}}
    <template x-for="val in selected" :key="val">
        <input type="hidden" :name="'{{ $name }}[]'" :value="val">
    </template>

    {{-- Trigger button --}}
    <button type="button" x-on:click="open = !open" class="w-full cursor-pointer flex items-center justify-between gap-2
               px-3 py-2 rounded-md border text-sm text-left
               border-gray-300 dark:border-gray-700
               bg-white dark:bg-gray-900
               text-gray-700 dark:text-gray-300
               shadow-sm focus:outline-none
               focus:border-indigo-500 dark:focus:border-indigo-600
               focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-600">
        <span x-show="selected.length === 0" class="text-gray-400 dark:text-gray-500">
            {{ $placeholder }}
        </span>
        <span x-show="selected.length > 0" x-text="`${selected.length} selected`"></span>
        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-transition class="absolute z-50 mt-1 w-full rounded-md border
               border-gray-300 dark:border-gray-700
               bg-white dark:bg-gray-900
               shadow-lg overflow-hidden">
        {{-- Search --}}
        <div class="p-2 border-b border-gray-100 dark:border-gray-800">
            <input type="text" x-model="search" placeholder="{{ $placeholder }}" class="w-full px-2 py-1 text-sm rounded border-0 outline-none
                       bg-gray-50 dark:bg-gray-800
                       text-gray-700 dark:text-gray-300
                       placeholder-gray-400 dark:placeholder-gray-500">
        </div>

        {{-- Options --}}
        <ul class="max-h-48 overflow-y-auto py-1">
            <template x-for="option in filtered" :key="option.value">
                <li x-on:click="toggle(option.value)" class="flex items-center gap-2 px-3 py-2 cursor-pointer text-sm
                           text-gray-700 dark:text-gray-300
                           hover:bg-indigo-50 dark:hover:bg-indigo-900/30" :class="isSelected(option.value)
                        ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'
                        : ''">
                    <span class="w-4 h-4 rounded border flex items-center justify-center shrink-0
                               border-gray-300 dark:border-gray-600" :class="isSelected(option.value)
                            ? 'bg-indigo-600 border-indigo-600'
                            : ''">
                        <svg x-show="isSelected(option.value)" class="w-3 h-3 text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span x-text="option.label"></span>
                </li>
            </template>
            <li x-show="filtered.length === 0" class="px-3 py-2 text-sm text-gray-400 dark:text-gray-500">
                {{ __('No Results') }}
            </li>
        </ul>
    </div>
</div>
