<nav x-data="{ open: false }"
    class="border-b border-gray-200 bg-white transition-colors duration-300 dark:border-white/10 dark:bg-black">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-6 lg:gap-10">
                <a href="{{ route('jobs.index') }}" class="flex shrink-0 items-center">
                    <x-app-logo width="104" />
                </a>

                <div class="hidden items-center gap-6 sm:flex">
                    @auth
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endauth

                    <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                        {{ __('Jobs') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden shrink-0 items-center gap-3 sm:flex">
                <x-dark-mode-toggle :label="false" />

                @auth
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex max-w-64 cursor-pointer items-center gap-2 rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-sm font-medium text-gray-700 shadow-sm transition-colors duration-200 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:border-white/20 dark:hover:bg-white/10 dark:hover:text-white">
                            <x-profile-logo width="32" />
                            <span class="max-w-36 truncate">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 shrink-0 opacity-60" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-gray-100 px-4 py-3 dark:border-white/10">
                            <div class="flex items-center gap-3">
                                <x-profile-logo width="36" />
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{
                                        Auth::user()->name }}</p>
                                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email
                                        }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="py-1">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
                @else
                <a href="{{ route('login') }}"
                    class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold text-gray-600 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-white/10 dark:hover:text-white">
                    {{ __('Log in') }}
                </a>

                <a href="{{ route('register') }}"
                    class="inline-flex items-center rounded-lg border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition-colors duration-200 hover:bg-gray-800 dark:border-white/10 dark:bg-white dark:text-gray-950 dark:hover:bg-gray-200">
                    {{ __('Register') }}
                </a>
                @endauth
            </div>

            <div class="-me-2 flex items-center gap-2 sm:hidden">
                <x-dark-mode-toggle :label="false" />

                <button type="button" @click="open = !open"
                    class="inline-flex cursor-pointer items-center justify-center rounded-lg p-2 text-gray-500 transition-colors duration-200 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/10 dark:hover:text-white"
                    aria-label="Toggle navigation">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': !open}"
        class="hidden border-t border-gray-100 px-4 pb-4 pt-2 transition-colors duration-300 sm:hidden dark:border-white/10">
        <div class="space-y-1">
            @auth
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @else
            <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                {{ __('Log in') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                {{ __('Register') }}
            </x-responsive-nav-link>
            @endauth

            <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
                {{ __('Jobs') }}
            </x-responsive-nav-link>
        </div>

        @auth
        <div class="mt-4 space-y-1 border-t border-gray-100 pt-4 dark:border-white/10">
            <div class="flex items-center gap-3 px-3 pb-2">
                <x-profile-logo width="40" />

                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
        @endauth
    </div>
</nav>
