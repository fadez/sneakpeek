<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    @head
    @fonts(['rubik'])
    @vite(['resources/css/app.css'])
</head>
<body class="http-error http-error-@yield('code')">
    <div class="http-error-container">
        <div class="http-error-card">
            <h1 class="http-error-code">@yield('code')</h1>

            @hasSection('imageUrl')
                <a href="{{ url('/') }}">
                    <img src="@yield('imageUrl')" class="http-error-image" alt="@yield('code')">
                </a>
            @endif

            <div class="http-error-message">@yield('message')</div>
        </div>
    </div>
</body>
</html>
