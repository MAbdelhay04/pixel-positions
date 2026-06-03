<section>
    <header class="mb-6">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">
            {{ __('Skills') }}
        </h3>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Add skills to your profile to help employers discover you.') }}
        </p>
    </header>

    @php
    use App\Models\Skill;

    $allSkills = Skill::orderBy('name')->pluck('name');
    $selectedSkills = old('skills')
    ? (is_array(old('skills')) ? implode(', ', old('skills')) : old('skills'))
    : $user->skills->pluck('name')->implode(', ');
    @endphp

    <form method="post" action="{{ route('profile.skills') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div data-token-picker data-token-options='@json($allSkills)' data-token-initial="{{ $selectedSkills }}"
            data-token-field="skills[]" data-token-max="15">

            <x-input-label for="profile_skills_search" :value="__('Your Skills')" />

            <div class="rounded-lg border border-gray-300 bg-white p-2 transition-colors duration-150
                focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600
                dark:border-white/10 dark:bg-black dark:focus-within:border-blue-700 dark:focus-within:ring-blue-700">
                <div data-token-chips class="mb-2 flex flex-wrap gap-2"></div>
                <input id="profile_skills_search" type="search" data-token-input autocomplete="off"
                    placeholder="Search or add skills…"
                    class="w-full border-0 bg-transparent px-1 py-1 text-sm text-gray-900 placeholder-gray-400 outline-none focus:ring-0 dark:text-white dark:placeholder-gray-600">
            </div>

            <div data-token-hidden-container></div>
            <div data-token-suggestions class="mt-3 flex flex-wrap gap-2"></div>

            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                Choose existing skills or type new ones. Press comma or Enter to add more than one. Maximum 15.
            </p>

            <p data-token-error class="mt-1 hidden text-xs text-red-500 dark:text-red-400"></p>
            <x-input-error :messages="$errors->get('skills')" />
            <x-input-error :messages="collect($errors->get('skills.*'))->flatten()->unique()->values()->all()" />
        </div>

        <div class="flex items-center gap-4 pt-1">
            <x-primary-button>{{ __('Save Skills') }}</x-primary-button>

            @if (session('status') === 'skills-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Saved.') }}
            </p>
            @endif
        </div>
    </form>

    @if ($user->skills->isNotEmpty())
    <div class="mt-6 border-t border-gray-100 pt-6 dark:border-white/10">
        <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
            {{ __('Current Skills') }}
        </p>
        <div class="flex flex-wrap gap-2">
            @foreach ($user->skills as $skill)
            <x-skill :skill="$skill" />
            @endforeach
        </div>
    </div>
    @endif
</section>
