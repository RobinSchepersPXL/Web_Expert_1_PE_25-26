@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-xl mx-auto bg-white shadow rounded p-6">
            <h1 class="text-2xl font-bold mb-4">Ticket bewerken</h1>
            <p class="text-sm text-gray-600 mb-6">Voor event: <b>{{ $event->title }}</b></p>

            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tickets.update', $ticket->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold mb-1">Prijs (€) *</label>
                    <input name="prijs" type="number" step="0.01" min="0"
                           value="{{ old('prijs', $ticket->prijs) }}"
                           class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Beschikbare aantal *</label>
                    <input name="beschikbare_aantal" type="number" min="1"
                           value="{{ old('beschikbare_aantal', $ticket->beschikbare_aantal) }}"
                           class="w-full border rounded px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Gereserveerd: {{ $ticket->gereserveerd_aantal }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Categorie (optioneel)</label>
                    <input name="categorie" type="text"
                           value="{{ old('categorie', $ticket->categorie) }}"
                           class="w-full border rounded px-3 py-2">
                </div>

                <div class="pt-2 flex gap-2">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Opslaan</button>
                    <a href="{{ route('events.show', $event) }}" class="px-4 py-2 rounded border">Annuleren</a>
                </div>
            </form>
        </div>
    </div>
@endsection
