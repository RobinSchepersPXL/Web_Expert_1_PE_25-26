@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold">Mijn profiel</h1>
                <a href="{{ route('events.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Naar events
                </a>
            </div>

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

            {{-- Persoonlijke gegevens + update --}}
            <div class="bg-white shadow rounded p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Persoonlijke gegevens</h2>

                <form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold mb-1">Naam *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">E-mail *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600 mb-3">
                            <span class="font-semibold">Rol:</span> {{ $user->role ?? '—' }}
                        </div>

                        <button class="bg-blue-600 text-white px-4 py-2 rounded">
                            Opslaan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Reservaties --}}
            <div class="bg-white shadow rounded p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Mijn reservaties</h2>

                @if($reservedEvents->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="border-b text-left text-gray-600">
                                <th class="py-2 pr-4">Event</th>
                                <th class="py-2 pr-4">Locatie</th>
                                <th class="py-2 pr-4">Start</th>
                                <th class="py-2 pr-4">Aantal</th>
                                <th class="py-2 pr-4"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reservedEvents as $r)
                                <tr class="border-b">
                                    <td class="py-2 pr-4 font-semibold">{{ $r->title }}</td>
                                    <td class="py-2 pr-4">{{ $r->location }}</td>
                                    <td class="py-2 pr-4">
                                        {{ $r->start_date ? \Carbon\Carbon::parse($r->start_date)->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td class="py-2 pr-4">{{ $r->totaal_aantal }}</td>
                                    <td class="py-2 pr-4">
                                        <a class="underline text-blue-700"
                                           href="{{ route('events.show', $r->event_id) }}">
                                            Bekijk
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">Je hebt nog geen tickets gereserveerd.</p>
                @endif
            </div>

            {{-- Favorieten --}}
            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-bold mb-4">Mijn favorieten</h2>

                @if($favoriteEvents->count())
                    <ul class="space-y-2">
                        @foreach($favoriteEvents as $f)
                            <li class="border rounded p-3 flex items-center justify-between">
                                <div>
                                    <div class="font-semibold">{{ $f->title }}</div>
                                    <div class="text-sm text-gray-600">
                                        📍 {{ $f->location }} •
                                        🗓️ {{ $f->start_date ? \Carbon\Carbon::parse($f->start_date)->format('d/m/Y H:i') : '—' }}
                                    </div>
                                </div>
                                <a class="underline text-blue-700"
                                   href="{{ route('events.show', $f->event_id) }}">
                                    Bekijk
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600">Je hebt nog geen favorieten.</p>
                @endif
            </div>

        </div>
    </div>
@endsection
