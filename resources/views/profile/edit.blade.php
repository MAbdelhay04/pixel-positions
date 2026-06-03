<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <x-profile-logo :user="Auth::user()" width="56" class="rounded-xl" />

            <div class="min-w-0">
                <h2 class="font-bold text-xl leading-tight">
                    {{ __('Profile') }}
                </h2>
                <p class="mt-1 truncate text-sm text-gray-500 dark:text-gray-400">
                    {{ Auth::user()->name }} - {{ Auth::user()->email }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Profile Photo --}}
            <div class="px-6 py-8 rounded-xl border transition-colors duration-300
                bg-white border-gray-200 shadow-sm
                dark:bg-white/5 dark:border-white/10">
                @include('profile.partials.update-logo-form')
            </div>

            {{-- Profile Info --}}
            <div class="px-6 py-8 rounded-xl border transition-colors duration-300
                bg-white border-gray-200 shadow-sm
                dark:bg-white/5 dark:border-white/10">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Skills --}}
            @if (Auth::user()->isCandidate())
            <div class="px-6 py-8 rounded-xl border transition-colors duration-300
                bg-white border-gray-200 shadow-sm
                dark:bg-white/5 dark:border-white/10">
                @include('profile.partials.update-skills-form')
            </div>
            @endif

            {{-- Password --}}
            <div class="px-6 py-8 rounded-xl border transition-colors duration-300
                bg-white border-gray-200 shadow-sm
                dark:bg-white/5 dark:border-white/10">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete Account --}}
            <div class="px-6 py-8 rounded-xl border transition-colors duration-300
                bg-white border-gray-200 shadow-sm
                dark:bg-white/5 dark:border-white/10">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
