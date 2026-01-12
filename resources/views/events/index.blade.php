{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Evenementen</h1>
                    <p class="text-gray-600 mt-1">
                        @auth
                            Jouw favorieten staan bovenaan.
                        @else
                            Overzicht gesorteerd op nieuwste eerst.
                        @endauth
                    </p>
                </div>

                @auth
                    @can('create', \App\Models\Event::class)
                        <a href="{{ route('events.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Nieuw event
                        </a>
                    @endcan
                @endauth
            </div>

            @if(session('status'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @php
                $favoriteIds = $favoriteIds ?? [];
                $favoriteSet = array_flip($favoriteIds);

                // Favorieten bovenaan (ingelogd). Guests blijven gewoon latest() uit controller.
                $sortedEvents = $events->sortByDesc(function($e) use ($favoriteSet) {
                    return isset($favoriteSet[$e->id]) ? 1 : 0;
                })->values();
            @endphp

            @if($sortedEvents->count() === 0)
                <div class="bg-white shadow rounded p-6 text-gray-700">
                    Geen events gevonden.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($sortedEvents as $event)
                        @php
                            $images = $event->images ?? [];
                            if (is_string($images)) $images = json_decode($images, true) ?? [];
                            $cover = count($images) ? asset('storage/' . $images[0]) : null;

                            $isFav = auth()->check() && in_array($event->id, $favoriteIds);
                        @endphp

                        <a href="{{ route('events.show', $event) }}"
                           class="group bg-white shadow hover:shadow-md transition rounded overflow-hidden block">
                            @if($cover)
                                <img src="{{ $cover }}" alt="Event cover" class="w-full h-44 object-cover">
                            @else
                                <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-500">
                                    Geen afbeelding
                                </div>
                            @endif

                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <h2 class="text-xl font-bold text-gray-900 group-hover:underline">
                                        {{ $event->title }}
                                    </h2>

                                    @auth
                                        @if($isFav)
                                            <span class="text-xs font-semibold bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                            ★ Favoriet
                                        </span>
                                        @endif
                                    @endauth
                                </div>

                                <p class="text-gray-600 mt-2">
                                    {{ \Illuminate\Support\Str::limit($event->description, 120) }}
                                </p>

                                <div class="mt-4 text-sm text-gray-600 space-y-1">
                                    <div>📍 {{ $event->location }}</div>
                                    <div>🗓️ {{ optional($event->start_date)->format('d/m/Y H:i') }}</div>
                                    @if($event->end_date)
                                        <div>⏳ t.e.m. {{ optional($event->end_date)->format('d/m/Y H:i') }}</div>
                                    @endif
                                </div>

                                <div class="mt-4 flex items-center justify-between text-sm">
                                    <div class="text-gray-800 font-semibold">
                                        @if(!is_null($event->price))
                                            € {{ number_format($event->price, 2, ',', '.') }}
                                        @else
                                            Gratis
                                        @endif
                                    </div>

                                    <div class="text-gray-500">
                                        @if(!is_null($event->capacity))
                                            👥 {{ $event->capacity }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
