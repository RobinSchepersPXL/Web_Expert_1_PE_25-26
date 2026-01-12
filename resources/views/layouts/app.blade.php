<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ticket Wave')</title>

    <link rel="stylesheet" href="{{ asset('ticketwave.css') }}">
</head>

<body>
<div class="tw-bg"></div>

<header class="tw-header">
    <div class="tw-header-inner">
        <a class="tw-brand" href="{{ route('events.index') }}">Ticket Wave</a>

        <nav class="tw-nav">
            <a href="{{ route('events.index') }}">Events</a>

            @auth
                <a href="{{ route('profile.show') }}">Profiel</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Uitloggen</button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login.show') }}">Log in</a>
                <a class="btn btn-primary" href="{{ route('register.show') }}">Register</a>
            @endguest
        </nav>
    </div>
</header>

<main>
    {{-- Full-width content for auth pages --}}
    @hasSection('content_full')
        @yield('content_full')
    @else
        <div class="tw-container">
            @yield('content')
        </div>
    @endif
</main>

<footer class="tw-footer">
    <div>© {{ date('Y') }} Ticket Wave</div>
    <div class="muted">Events & Tickets</div>
</footer>

</body>
</html>
