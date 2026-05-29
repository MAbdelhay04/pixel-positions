<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <script>
        (function () {
            try {
                var saved = localStorage.getItem('theme');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var dark = saved === 'dark' || (!saved && prefersDark);

                document.documentElement.classList.toggle('dark', dark);
                document.documentElement.style.backgroundColor = dark ? '#000000' : '#f9fafb';
            } catch (error) {
                document.documentElement.style.backgroundColor = '#f9fafb';
            }
        })();
    </script>

    <style>
        html {
            background: #f9fafb;
            color-scheme: light;
        }

        html.dark {
            background: #000;
            color-scheme: dark;
        }

        html.dark body {
            background: #000;
            color: #fff;
        }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{ $slot }}
