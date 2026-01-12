{{-- resources/views/events/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold">Nieuw evenement</h1>
                <a href="{{ route('events.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                    ← Terug naar overzicht
                </a>
            </div>

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data"
                  class="bg-white shadow rounded p-6 space-y-5">
                @csrf

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-bold text-gray-700 mb-1">Titel *</label>
                    <input id="title" name="title" type="text" required
                           value="{{ old('title') }}"
                           class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
                    @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Beschrijving *</label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Location --}}
                <div>
                    <label for="location" class="block text-sm font-bold text-gray-700 mb-1">Locatie *</label>
                    <input id="location" name="location" type="text" required
                           value="{{ old('location') }}"
                           class="w-full border rounded px-3 py-2 @error('location') border-red-500 @enderror">
                    @error('location') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Start date --}}
                    <div>
                        <label for="start_date" class="block text-sm font-bold text-gray-700 mb-1">Startdatum *</label>
                        <input id="start_date" name="start_date" type="datetime-local" required
                               value="{{ old('start_date') }}"
                               class="w-full border rounded px-3 py-2 @error('start_date') border-red-500 @enderror">
                        @error('start_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- End date --}}
                    <div>
                        <label for="end_date" class="block text-sm font-bold text-gray-700 mb-1">Einddatum (optioneel)</label>
                        <input id="end_date" name="end_date" type="datetime-local"
                               value="{{ old('end_date') }}"
                               class="w-full border rounded px-3 py-2 @error('end_date') border-red-500 @enderror">
                        @error('end_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Capacity --}}
                    <div>
                        <label for="capacity" class="block text-sm font-bold text-gray-700 mb-1">Capaciteit (optioneel)</label>
                        <input id="capacity" name="capacity" type="number" min="1"
                               value="{{ old('capacity') }}"
                               class="w-full border rounded px-3 py-2 @error('capacity') border-red-500 @enderror">
                        @error('capacity') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Price --}}
                    <div>
                        <label for="price" class="block text-sm font-bold text-gray-700 mb-1">Prijs (€) (optioneel)</label>
                        <input id="price" name="price" type="number" min="0" step="0.01"
                               value="{{ old('price') }}"
                               class="w-full border rounded px-3 py-2 @error('price') border-red-500 @enderror">
                        @error('price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Images --}}
                <div>
                    <label for="images" class="block text-sm font-bold text-gray-700 mb-1">Afbeeldingen (optioneel)</label>
                    <input id="images" name="images[]" type="file" multiple accept="image/*"
                           class="w-full border rounded px-3 py-2 @error('images') border-red-500 @enderror @error('images.*') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Meerdere afbeeldingen toegestaan (max 10, max 5MB per bestand).</p>
                    @error('images') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    @error('images.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t flex items-center gap-3">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded">
                        Opslaan
                    </button>
                    <a href="{{ route('events.index') }}"
                       class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
