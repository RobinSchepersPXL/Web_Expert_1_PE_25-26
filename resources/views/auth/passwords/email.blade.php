@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-md mx-auto bg-white shadow rounded p-6">
            <h1 class="text-2xl font-bold mb-4">Wachtwoord vergeten</h1>

            @if(session('status'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border rounded px-3 py-2">
                </div>

                <button class="w-full bg-gray-900 text-white py-2 rounded">
                    Stuur reset link
                </button>
            </form>

            <div class="mt-4 text-sm">
                <a href="{{ route('login.show') }}" class="underline text-blue-700">Terug naar login</a>
            </div>
        </div>
    </div>
@endsection
