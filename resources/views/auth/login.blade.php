@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto p-6">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- e-mail -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full" />
            </div>

            <!-- wachtwoord -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">Wachtwoord</label>
                <input id="password" type="password" name="password" required class="mt-1 block w-full" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Inloggen</button>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                        Wachtwoord vergeten?
                    </a>
                @endif
            </div>
        </form>
    </div>
@endsection
