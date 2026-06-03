@php
use App\Models\Category;
use App\Models\Skill;
use App\Models\Tag;
use App\Enums\JobLocation;
use App\Enums\JobType;
use App\Enums\JobStatus;
$job = $job ?? null;
$categories = Category::orderBy('name')->get();
$skills = Skill::orderBy('name')->pluck('name');
$tags = Tag::orderBy('name')->pluck('name');

$locationOptions = collect(JobLocation::cases())->map(fn ($location) => [
'value' => $location->value,
'label' => $location->label(),
])->all();

$typeOptions = collect(JobType::cases())->map(fn ($type) => [
'value' => $type->value,
'label' => $type->label(),
])->all();

$statusOptions = collect(JobStatus::cases())->map(fn ($status) => [
'value' => $status->value,
'label' => $status->label(),
])->all();

$selectedLocation = $job?->location?->value ?? JobLocation::OnSite->value;
$selectedType = $job?->type?->value ?? JobType::FullTime->value;
$selectedStatus = $job?->status?->value ?? JobStatus::Draft->value;

$oldSkills = old('skills');
$selectedSkills = $oldSkills
? (is_array($oldSkills) ? implode(', ', $oldSkills) : $oldSkills)
: ($job ? $job->skills->pluck('name')->implode(', ') : '');

$oldTags = old('tags');
$selectedTags = $oldTags
? (is_array($oldTags) ? implode(', ', $oldTags) : $oldTags)
: ($job ? $job->tags->pluck('name')->implode(', ') : '');

@endphp

<form method="POST" action="{{ $action }}" class="space-y-8">
    @csrf

    @isset($method)
    @method($method)
    @endisset

    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <x-input-label for="title" :value="__('Job Title')" />
                <x-text-input id="title" name="title" type="text" :value="old('title', $job?->title)" required
                    placeholder="Senior Laravel Developer" />
                <x-input-error :messages="$errors->get('title')" />
            </div>

            <div>
                <x-input-label for="url" :value="__('Job URL')" />
                <x-text-input id="url" name="url" type="url" :value="old('url', $job?->url)"
                    placeholder="https://example.com/jobs/role" />
                <x-input-error :messages="$errors->get('url')" />
            </div>

            <div>
                <x-input-label for="salary_range" :value="__('Salary Range')" />
                <x-text-input id="salary_range" name="salary_range" type="text"
                    :value="old('salary_range', $job?->salary_range)" placeholder="$90k - $120k" />
                <x-input-error :messages="$errors->get('salary_range')" />
            </div>

            <x-single-select name="category_id"
                :options="$categories->map(fn($c) => ['value' => $c->id, 'label' => ucwords($c->name)])->all()"
                :selected="old('category_id', $job?->category_id ?? '')" placeholder="No category" />

            <div class="md:col-span-2">
                <x-input-label for="description" :value="__('Description')" />
                <textarea id="description" name="description" rows="7" required
                    placeholder="Describe the role, responsibilities, and what makes this opportunity worth applying for."
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 transition-all duration-150 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder-gray-600 dark:focus:border-blue-700 dark:focus:ring-blue-700">{{ old('description', $job?->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" />
            </div>
        </div>
    </section>

    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="grid gap-6">
            <div>
                <x-input-label :value="__('Location')" />
                <x-radio-group name="location" :options="$locationOptions"
                    :selected="old('location', $selectedLocation)" />
                <x-input-error :messages="$errors->get('location')" />
            </div>

            <div>
                <x-input-label :value="__('Job Type')" />
                <x-radio-group name="type" :options="$typeOptions" :selected="old('type', $selectedType)" />
                <x-input-error :messages="$errors->get('type')" />
            </div>

            <div>
                <x-input-label :value="__('Status')" />
                <x-radio-group name="status" :options="$statusOptions" :selected="old('status', $selectedStatus)" />
                <x-input-error :messages="$errors->get('status')" />
            </div>
        </div>
    </section>

    <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="grid gap-5 md:grid-cols-2">

            <div data-token-picker data-token-options='@json($skills)' data-token-initial="{{ $selectedSkills }}"
                data-token-field="skills[]" data-token-max="10">
                <x-input-label for="skills_search" :value="__('Skills')" />

                <div
                    class="rounded-lg border border-gray-300 bg-white p-2 transition-colors duration-150 focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600 dark:border-white/10 dark:bg-black dark:focus-within:border-blue-700 dark:focus-within:ring-blue-700">
                    <div data-token-chips class="mb-2 flex flex-wrap gap-2"></div>

                    <input id="skills_search" type="search" data-token-input autocomplete="off"
                        placeholder="Search or add skills"
                        class="w-full border-0 bg-transparent px-1 py-1 text-sm text-gray-900 placeholder-gray-400 outline-none focus:ring-0 dark:text-white dark:placeholder-gray-600">
                </div>

                <div data-token-hidden-container></div>

                <div data-token-suggestions class="mt-3 flex flex-wrap gap-2"></div>

                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Choose existing skills or type new ones. Press comma or Enter to add more than one.
                </p>

                <p data-token-error class="mt-1 hidden text-xs text-red-500 dark:text-red-400"></p>

                <x-input-error :messages="$errors->get('skills')" />
                <x-input-error :messages="collect($errors->get('skills.*'))->flatten()->unique()->values()->all()" />

            </div>

            <div data-token-picker data-token-options='@json($tags)' data-token-initial="{{ $selectedTags }}"
                data-token-field="tags[]" data-token-max="5">
                <x-input-label for="tags_search" :value="__('Tags')" />

                <div
                    class="rounded-lg border border-gray-300 bg-white p-2 transition-colors duration-150 focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600 dark:border-white/10 dark:bg-black dark:focus-within:border-blue-700 dark:focus-within:ring-blue-700">
                    <div data-token-chips class="mb-2 flex flex-wrap gap-2"></div>

                    <input id="tags_search" type="search" data-token-input autocomplete="off"
                        placeholder="Search or add tags"
                        class="w-full border-0 bg-transparent px-1 py-1 text-sm text-gray-900 placeholder-gray-400 outline-none focus:ring-0 dark:text-white dark:placeholder-gray-600">
                </div>

                <div data-token-hidden-container></div>

                <div data-token-suggestions class="mt-3 flex flex-wrap gap-2"></div>

                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Choose existing tags or type new ones. Press comma or Enter to add more than one.
                </p>

                <p data-token-error class="mt-1 hidden text-xs text-red-500 dark:text-red-400"></p>

                <x-input-error :messages="$errors->get('tags')" />
                <x-input-error :messages="collect($errors->get('tags.*'))->flatten()->unique()->values()->all()" />
            </div>

        </div>
    </section>

    <div class="flex items-center justify-end gap-3">
        <x-secondary-button type="button" onclick="window.history.back()">
            {{ __('Cancel') }}
        </x-secondary-button>

        <x-primary-button>
            {{ $submitLabel }}
        </x-primary-button>
    </div>
</form>
