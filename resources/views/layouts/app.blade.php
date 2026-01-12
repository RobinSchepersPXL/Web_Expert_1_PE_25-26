<!-- File: resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'TicketWave'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
<div id="app" class="min-h-screen flex flex-col">

    {{-- Topbar --}}
    <header class="bg-white/90 dark:bg-gray-800/90 backdrop-blur border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="font-bold text-lg tracking-tight">
                    {{ config('app.name', 'TicketWave') }}
                </a>

                <nav class="hidden sm:flex items-center gap-2 text-sm">
                    <a href="{{ route('events.index') }}"
                       class="px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                        Events
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                            Dashboard
                        </a>
                    @endauth
                </nav>
            </div>

            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login.show') }}"
                       class="px-3 py-2 rounded text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-700">
                        Log in
                    </a>

                    <a href="{{ route('register.show') }}"
                       class="px-3 py-2 rounded text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700">
                        Register
                    </a>
                @endguest

                @auth
                    {{-- Logout MUST be POST --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-3 py-2 rounded text-sm font-semibold bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600">
                            Uitloggen
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    {{-- Page content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="text-center text-sm p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
    </footer>
</div>
</body>
</html>
