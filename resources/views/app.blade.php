<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    @head
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>
<body>
    <div id="app" data-test="app"></div>
</body>
</html>
