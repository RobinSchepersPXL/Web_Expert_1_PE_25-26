{{-- resources/views/events/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold">{{ $event->title }}</h1>
                    <div class="text-gray-600 mt-2">
                        <span class="mr-2">📍 {{ $event->location }}</span>
                        <span class="mx-2">•</span>
                        <span>🗓️ {{ optional($event->start_date)->format('d/m/Y H:i') }}</span>
                        @if($event->end_date)
                            <span class="mx-2">→</span>
                            <span>{{ optional($event->end_date)->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>

                    {{-- Favorite status badge --}}
                    @auth
                        <div id="favoriteBadge"
                             class="mt-2 inline-block text-xs font-semibold bg-yellow-100 text-yellow-800 px-2 py-1 rounded {{ empty($isFavorite) ? 'hidden' : '' }}">
                            ★ Dit event staat in je favorieten
                        </div>
                    @endauth
                </div>

                <div class="flex gap-2 items-center">
                    {{-- Favorite toggle button (AJAX) --}}
                    @auth
                        <button
                            id="favoriteBtn"
                            data-url="{{ route('events.favorite.toggle', $event) }}"
                            data-initial="{{ !empty($isFavorite) ? '1' : '0' }}"
                            class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-bold py-2 px-4 rounded"
                            type="button"
                        >
                            {{ !empty($isFavorite) ? '★ In favorieten' : '☆ Voeg toe aan favorieten' }}
                        </button>
                    @else
                        <span class="text-sm text-gray-600">Log in om favorieten te gebruiken.</span>
                    @endauth

                    {{-- Admin/owner actions --}}
                    @can('update', $event)
                        <a href="{{ route('events.edit', $event) }}"
                           class="bg-gray-900 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
                            Bewerken
                        </a>
                    @endcan

                    @can('delete', $event)
                        <form action="{{ route('events.destroy', $event) }}" method="POST"
                              onsubmit="return confirm('Ben je zeker dat je dit event wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Verwijderen
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            @if(session('status'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            {{-- AJAX error box --}}
            @auth
                <div id="favoriteError" class="hidden bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-4"></div>
            @endauth

            @php
                $images = $event->images ?? [];
                if (is_string($images)) $images = json_decode($images, true) ?? [];
            @endphp

            {{-- Afbeeldingen --}}
            @if(count($images) > 0)
                <div class="bg-white shadow rounded p-5 mb-6">
                    <h2 class="text-xl font-bold mb-4">Afbeeldingen</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($images as $path)
                            <img src="{{ asset('storage/' . $path) }}"
                                 class="w-full h-52 object-cover rounded"
                                 alt="Event afbeelding">
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Event info --}}
            <div class="bg-white shadow rounded p-6 mb-6">
                <h2 class="text-xl font-bold mb-3">Beschrijving</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $event->description }}</p>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-gray-50 rounded p-4">
                        <div class="text-gray-500">Prijs</div>
                        <div class="font-bold text-gray-900 mt-1">
                            @if(!is_null($event->price))
                                € {{ number_format($event->price, 2, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded p-4">
                        <div class="text-gray-500">Capaciteit</div>
                        <div class="font-bold text-gray-900 mt-1">
                            {{ $event->capacity ?? '—' }}
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded p-4">
                        <div class="text-gray-500">Organisator</div>
                        <div class="font-bold text-gray-900 mt-1">
                            {{ optional($event->user)->name ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tickets --}}
            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-bold mb-4">Tickets</h2>

                @if(isset($tickets) && $tickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="text-left text-gray-600 border-b">
                                <th class="py-2 pr-4">Type</th>
                                <th class="py-2 pr-4">Prijs</th>
                                <th class="py-2 pr-4">Aantal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $ticket)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">{{ $ticket->name ?? $ticket->type ?? 'Ticket' }}</td>
                                    <td class="py-2 pr-4">
                                        @if(isset($ticket->price))
                                            € {{ number_format($ticket->price, 2, ',', '.') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="py-2 pr-4">{{ $ticket->quantity ?? $ticket->available ?? '—' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">Er zijn momenteel geen tickets beschikbaar voor dit event.</p>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                    ← Terug naar overzicht
                </a>
            </div>
        </div>
    </div>

    {{-- Favorite toggle script --}}
    @auth
        <script>
            (function () {
                const btn = document.getElementById('favoriteBtn');
                if (!btn) return;

                const badge = document.getElementById('favoriteBadge');
                const errBox = document.getElementById('favoriteError');

                const setUI = (favorited) => {
                    btn.textContent = favorited ? '★ In favorieten' : '☆ Voeg toe aan favorieten';
                    if (badge) badge.classList.toggle('hidden', !favorited);
                };

                // init UI from server value
                setUI(btn.dataset.initial === '1');

                btn.addEventListener('click', async () => {
                    if (errBox) errBox.classList.add('hidden');

                    try {
                        const res = await fetch(btn.dataset.url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });

                        const data = await res.json();

                        if (!res.ok) {
                            const msg = data?.message ?? 'Er ging iets mis.';
                            if (errBox) {
                                errBox.textContent = msg;
                                errBox.classList.remove('hidden');
                            } else {
                                alert(msg);
                            }
                            return;
                        }

                        setUI(!!data.favorited);
                    } catch (e) {
                        const msg = 'Netwerkfout. Probeer opnieuw.';
                        if (errBox) {
                            errBox.textContent = msg;
                            errBox.classList.remove('hidden');
                        } else {
                            alert(msg);
                        }
                    }
                });
            })();
        </script>
    @endauth
@endsection
