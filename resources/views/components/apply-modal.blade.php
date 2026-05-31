@props(['job'])

<x-modal name="apply-{{ $job->id }}" :show="$errors->applyJob->isNotEmpty()">
    <form method="POST" action="{{ route('applications.store', $job) }}" enctype="multipart/form-data" class="p-6">
        @csrf

        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-1">
            {{ __('Apply for: ') . $job->title }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            {{ __('Upload your resume and write a cover letter to apply.') }}
        </p>

        <div class="mb-5">
            <x-input-label for="resume" value="{{ __('Resume') }}" />
            <x-file-input id="resume" name="resume" accept=".pdf,.docx" />
            <x-input-error :messages="$errors->applyJob->get('resume')" />
        </div>

        <div class="mb-6">
            <x-input-label for="cover_letter" value="{{ __('Cover Letter') }}" />
            <textarea id="cover_letter" name="cover_letter" rows="5"
                placeholder="{{ __('Tell the employer why you\'re a great fit...') }}"
                class="block w-full px-3.5 py-2.5 rounded-lg border text-sm transition-all duration-150 focus:outline-none focus:ring-1
                bg-white border-gray-300 text-gray-900 placeholder-gray-400 focus:border-blue-600 focus:ring-blue-600
                dark:bg-white/5 dark:border-white/10 dark:text-white dark:placeholder-gray-600 dark:focus:border-blue-700 dark:focus:ring-blue-700">{{ old('cover_letter') }}</textarea>
            <x-input-error :messages="$errors->applyJob->get('cover_letter')" />
        </div>

        <div class="flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-primary-button>
                {{ __('Submit Application') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
