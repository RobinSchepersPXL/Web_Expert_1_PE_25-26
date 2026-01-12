@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-md mx-auto bg-white shadow rounded p-6">
            <h1 class="text-2xl font-bold mb-4">Nieuw wachtwoord instellen</h1>

            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- BELANGRIJK: token + email moeten meegestuurd worden --}}
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ old('email', $email) }}">

                <div>
                    <label class="block text-sm font-semibold mb-1">Nieuw wachtwoord</label>
                    <input type="password" name="password" required
                           class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Bevestig wachtwoord</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border rounded px-3 py-2">
                </div>

                <button class="w-full bg-gray-900 text-white py-2 rounded">
                    Wachtwoord wijzigen
                </button>
            </form>
        </div>
    </div>
@endsection
