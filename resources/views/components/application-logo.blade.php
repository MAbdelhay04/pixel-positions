<img {{ $attributes->merge(['width' => 100, 'class' => 'dark:hidden']) }}
src="{{ Vite::asset('resources/images/logo-light.svg') }}"
alt="{{ config('app.name') }}">

<img {{ $attributes->merge(['width' => 100, 'class' => 'hidden dark:inline']) }}
src="{{ Vite::asset('resources/images/logo-dark.svg') }}"
alt="{{ config('app.name') }}">
