<!-- File: resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Vite (Laravel 9+) — adjust if your project uses Mix or plain assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
<div id="app" class="min-h-screen flex flex-col">
    <header class="shadow-sm bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <a href="{{ url('/') }}" class="font-semibold text-lg">{{ config('app.name', 'Laravel') }}</a>
        </div>
    </header>

    <main class="flex-1">
        {{-- Your views should provide a `content` section --}}
        @yield('content')
    </main>

    <footer class="text-center text-sm p-4 bg-white dark:bg-gray-800">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
    </footer>
</div>
</body>
</html>
