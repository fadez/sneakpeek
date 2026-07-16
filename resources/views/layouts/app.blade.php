<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="Secure, one-time secret sharing made simple." />

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@700&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer">

    {{-- OpenGraph --}}
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:title" content="{{ config('app.name') }}" />
    <meta property="og:description" content="Secure, one-time secret sharing made simple." />
    <meta property="og:image" content="{{ asset('og.png') }}" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:type" content="website" />

    {{-- OpenGraph: X (Twitter) --}}
    <meta name="twitter:title" content="{{ config('app.name') }}" />
    <meta name="twitter:description" content="Secure, one-time secret sharing made simple." />
    <meta name="twitter:image" content="{{ asset('og.png') }}" />
    <meta name="twitter:card" content="summary_large_image" />

    {{-- Styles / Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body class="@yield('bodyClasses')">
    @yield('content')
</body>
</html>
