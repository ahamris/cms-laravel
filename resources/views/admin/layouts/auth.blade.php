<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - OpenPublication.eu</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- FontAwesome Pro Links --}}
    <link href="{{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/fontawesome/css/brands.css') }}" rel="stylesheet"/>

    {{-- Styles / Scripts --}}
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')

    {{-- Dynamic Theme Variables --}}
    <style>
        :root {
            --color-primary: {{ get_setting('theme_color_primary', '#081245') }};
            --color-secondary: {{ get_setting('theme_color_secondary', '#0073e6') }};
            --color-natural: {{ get_setting('theme_color_natural', '#dfd4d4') }};
        }
    </style>

</head>
<body class="min-h-screen flex items-center justify-center">
    @yield('content')
    @stack('scripts')
</body>
</html>
