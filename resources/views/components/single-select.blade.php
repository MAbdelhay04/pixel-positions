@props([
'name' => '',
'options' => [],
'selected' => '',
'placeholder' => 'Select…',
])

<div x-data="{
        open: false,
        search: '',
        selected: @js(old($name, $selected) ?? ''),
        options: @js($options),
        get filtered() {
            return this.options.filter(o =>
                o.label.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        get selectedLabel() {
            const found = this.options.find(o => o.value == this.selected);
            return found ? found.label : '';
        },
        choose(val) {
            this.selected = val;
            this.open = false;
            this.search = '';
        },
        isSelected(val) {
            return this.selected == val;
        }
    }" x-on:click.outside="open = false" class="relative">

    {{-- Hidden input --}}
    <input type="hidden" :name="'{{ $name }}'" :value="selected">

    {{-- Trigger button --}}
    <button type="button" x-on:click="open = !open" class="flex w-full cursor-pointer items-center justify-between gap-2 rounded-lg border px-3.5 py-2.5 text-left text-sm transition-all duration-150 focus:outline-none focus:ring-1
            border-gray-300 bg-white text-gray-900 focus:border-blue-600 focus:ring-blue-600
            dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-blue-700 dark:focus:ring-blue-700">
        <span x-show="selected === '' || selected === null" class="text-gray-400 dark:text-gray-600">
            {{ $placeholder }}
        </span>
        <span x-show="selected !== '' && selected !== null" x-text="selectedLabel"
            class="text-gray-900 dark:text-white"></span>
        <svg class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-transition class="absolute z-50 mt-1 w-full overflow-hidden rounded-lg border shadow-lg
            border-gray-200 bg-white
            dark:border-white/10 dark:bg-[#111111]" style="display: none;">

        {{-- Search --}}
        <div class="border-b border-gray-100 p-2 dark:border-white/10">
            <input type="text" x-model="search" placeholder="{{ $placeholder }}" class="w-full rounded-md border-0 bg-gray-50 px-2 py-1.5 text-sm text-gray-700 placeholder-gray-400 outline-none
                    dark:bg-[#1a1a1a] dark:text-gray-300 dark:placeholder-gray-600">
        </div>

        {{-- Options --}}
        <ul class="max-h-48 overflow-y-auto py-1">
            <template x-for="option in filtered" :key="option.value">
                <li x-on:click="choose(option.value)" class="flex cursor-pointer items-center gap-2.5 px-3 py-2 text-sm transition-colors duration-100
                        text-gray-700 hover:bg-gray-100
                        dark:text-gray-300 dark:hover:bg-white/5"
                    :class="isSelected(option.value) ? 'bg-gray-50 dark:bg-white/5' : ''">

                    {{-- Radio indicator --}}
                    <span
                        class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full border transition-colors duration-100"
                        :class="isSelected(option.value)
                            ? 'border-gray-700 bg-gray-900 dark:border-white/30 dark:bg-white/20'
                            : 'border-gray-300 dark:border-white/20'">
                        <span x-show="isSelected(option.value)" class="h-1.5 w-1.5 rounded-full bg-white"></span>
                    </span>

                    <span x-text="option.label"></span>
                </li>
            </template>

            <li x-show="filtered.length === 0" class="px-3 py-2 text-sm text-gray-400 dark:text-gray-600">
                {{ __('No results') }}
            </li>
        </ul>
    </div>
</div>
