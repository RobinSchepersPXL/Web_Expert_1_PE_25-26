{{-- resources/views/events/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold">{{ $event->title }}</h1>

                    <div class="text-gray-600 mt-2">
                        📍 {{ $event->location }} •
                        🗓️ {{ optional($event->start_date)->format('d/m/Y H:i') }}
                        @if($event->end_date)
                            → {{ optional($event->end_date)->format('d/m/Y H:i') }}
                        @endif
                    </div>

                    @auth
                        <div id="favoriteBadge"
                             class="mt-2 inline-block text-xs font-semibold bg-yellow-100 text-yellow-800 px-2 py-1 rounded {{ empty($isFavorite) ? 'hidden' : '' }}">
                            ★ Dit event staat in je favorieten
                        </div>
                    @endauth
                </div>

                {{-- Acties --}}
                <div class="flex gap-2">
                    @auth
                        <button
                            id="favoriteBtn"
                            data-url="{{ route('events.favorite.toggle', $event) }}"
                            data-initial="{{ !empty($isFavorite) ? '1' : '0' }}"
                            class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-bold py-2 px-4 rounded"
                            type="button">
                            {{ !empty($isFavorite) ? '★ In favorieten' : '☆ Voeg toe aan favorieten' }}
                        </button>
                    @endauth

                    @can('update', $event)
                        <a href="{{ route('events.edit', $event) }}"
                           class="bg-gray-900 text-white px-4 py-2 rounded">
                            Bewerken
                        </a>
                    @endcan

                    @can('delete', $event)
                        <form method="POST" action="{{ route('events.destroy', $event) }}">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 text-white px-4 py-2 rounded"
                                    onclick="return confirm('Event verwijderen?')">
                                Verwijderen
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            {{-- Meldingen --}}
            @if(session('status'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @auth
                <div id="favoriteError" class="hidden bg-red-100 text-red-800 p-3 rounded mb-4"></div>
            @endauth

            {{-- Afbeeldingen --}}
            @if(!empty($event->images))
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    @foreach($event->images as $img)
                        <img src="{{ asset('storage/'.$img) }}" class="rounded object-cover h-40 w-full">
                    @endforeach
                </div>
            @endif

            {{-- Beschrijving --}}
            <div class="bg-white p-6 rounded shadow mb-6">
                <h2 class="text-xl font-bold mb-2">Beschrijving</h2>
                <p>{{ $event->description }}</p>
            </div>

            {{-- Tickets --}}
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Tickets</h2>

                @if($tickets->count())
                    <table class="w-full text-sm mb-4">
                        <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Categorie</th>
                            <th class="text-left py-2">Prijs</th>
                            <th class="text-left py-2">Beschikbaar</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            @php
                                $beschikbaar = $ticket->beschikbare_aantal - $ticket->gereserveerd_aantal;
                            @endphp
                            <tr class="border-b">
                                <td class="py-2">{{ $ticket->categorie ?? 'Ticket' }}</td>
                                <td class="py-2">€ {{ number_format($ticket->prijs, 2, ',', '.') }}</td>
                                <td class="py-2">{{ $beschikbaar }}</td>
                                <td class="py-2">
                                    @auth
                                        <form method="POST" action="{{ route('tickets.reserve', $ticket->id) }}">
                                            @csrf
                                            <input type="number" name="aantal" min="1" max="{{ $beschikbaar }}" value="1"
                                                   class="w-16 border rounded px-2 py-1">
                                            <button class="bg-blue-600 text-white px-3 py-1 rounded">
                                                Reserveer
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">Log in om te reserveren</span>
                                    @endauth
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-600">Geen tickets beschikbaar.</p>
                @endif
            </div>

            <a href="{{ route('events.index') }}" class="block mt-6 text-gray-600">
                ← Terug naar overzicht
            </a>
        </div>
    </div>

    {{-- FAVORITE AJAX SCRIPT (GEEN BLADE HIERBINNEN) --}}
    @auth
        <script>
            (function () {
                const btn = document.getElementById('favoriteBtn');
                if (!btn) return;

                const badge = document.getElementById('favoriteBadge');
                const err = document.getElementById('favoriteError');

                const setUI = (on) => {
                    btn.textContent = on ? '★ In favorieten' : '☆ Voeg toe aan favorieten';
                    badge?.classList.toggle('hidden', !on);
                };

                setUI(btn.dataset.initial === '1');

                btn.addEventListener('click', async () => {
                    try {
                        const res = await fetch(btn.dataset.url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message ?? 'Fout');

                        setUI(data.favorited);
                    } catch (e) {
                        err.textContent = e.message;
                        err.classList.remove('hidden');
                    }
                });
            })();
        </script>
    @endauth
@endsection
