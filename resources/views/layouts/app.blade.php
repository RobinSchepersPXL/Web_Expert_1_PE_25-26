<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ticket Wave')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-100">
<div id="app" class="min-h-screen flex flex-col">

    {{-- Full-page blue glow background (like your screenshot) --}}
    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-950 to-slate-950"></div>
        <div class="absolute -top-56 left-1/2 h-[520px] w-[980px] -translate-x-1/2 rounded-full bg-blue-600/12 blur-3xl"></div>
        <div class="absolute top-20 left-1/3 h-[420px] w-[720px] -translate-x-1/2 rounded-full bg-indigo-500/10 blur-3xl"></div>
    </div>

    {{-- Navbar (minimal) --}}
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/70 backdrop-blur">
        <div class="w-full px-5 py-3 flex items-center justify-between">
            <a href="{{ route('events.index') }}" class="font-extrabold tracking-tight text-white text-lg">
                Ticket Wave
            </a>

            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('events.index') }}" class="nav-link">Events</a>

                @auth
                    <a href="{{ route('profile.show') }}" class="nav-link">Profiel</a>
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                @endauth

                @guest
                    <a href="{{ route('login.show') }}" class="nav-link">Log in</a>
                    <a href="{{ route('register.show') }}" class="btn btn-primary">Register</a>
                @endguest

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Uitloggen</button>
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Main --}}
    <main class="flex-1 w-full">
        {{-- Full-width slot (login/register/reset pages) --}}
        @hasSection('content_full')
            @yield('content_full')
        @else
            <div class="mx-auto max-w-7xl px-5 py-8">
                @yield('content')
            </div>
        @endif
    </main>

    <footer class="border-t border-white/10 bg-slate-950/60">
        <div class="w-full px-5 py-6 text-sm text-slate-300 flex flex-col sm:flex-row gap-2 items-center justify-between">
            <div>© {{ date('Y') }} Ticket Wave</div>
            <div class="flex gap-4">
                <a href="{{ route('events.index') }}" class="underline hover:text-white">Events</a>
                @auth
                    <a href="{{ route('profile.show') }}" class="underline hover:text-white">Profiel</a>
                @endauth
            </div>
        </div>
    </footer>

</div>
</body>
</html>
