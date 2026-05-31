@props(['width' => 100])

<img {{ $attributes->merge(['width' => $width, 'class' => 'dark:hidden']) }}
src="/images/logo-light.svg"
alt="{{ config('app.name') }}"
>

<img {{ $attributes->merge(['width' => $width, 'class' => 'hidden dark:inline']) }}
src="/images/logo-dark.svg"
alt="{{ config('app.name') }}"
>
