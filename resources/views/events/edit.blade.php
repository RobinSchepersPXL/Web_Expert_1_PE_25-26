@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">Evenement Bewerken</h1>

            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
                @csrf
                @method('PUT')

                {{-- Titel --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                        Titel *
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title', $event->title) }}"
                        required
                    >
                    @error('title')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Beschrijving --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Beschrijving *
                    </label>
                    <textarea
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                        id="description"
                        name="description"
                        rows="5"
                        required
                    >{{ old('description', $event->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Locatie --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="location">
                        Locatie *
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('location') border-red-500 @enderror"
                        id="location"
                        type="text"
                        name="location"
                        value="{{ old('location', $event->location) }}"
                        required
                    >
                    @error('location')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Start Datum --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                        Start Datum *
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror"
                        id="start_date"
                        type="datetime-local"
                        name="start_date"
                        value="{{ old('start_date', \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i')) }}"
                        required
                    >
                    @error('start_date')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- End Datum --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                        Eind Datum (optioneel)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror"
                        id="end_date"
                        type="datetime-local"
                        name="end_date"
                        value="{{ old('end_date', $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') : '') }}"
                    >
                    @error('end_date')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Capaciteit --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="capacity">
                        Capaciteit (optioneel)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('capacity') border-red-500 @enderror"
                        id="capacity"
                        type="number"
                        name="capacity"
                        min="1"
                        value="{{ old('capacity', $event->capacity) }}"
                    >
                    @error('capacity')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Prijs --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                        Prijs in € (optioneel)
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror"
                        id="price"
                        type="number"
                        step="0.01"
                        name="price"
                        min="0"
                        value="{{ old('price', $event->price) }}"
                    >
                    @error('price')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Huidige Afbeeldingen --}}
                @php
                    $currentImages = $event->images ?? [];
                    if (is_string($currentImages)) {
                        $currentImages = json_decode($currentImages, true) ?? [];
                    }
                @endphp

                @if(count($currentImages) > 0)
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Huidige Afbeeldingen
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($currentImages as $imagePath)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Event afbeelding" class="w-full h-32 object-cover rounded shadow">
                                    <label class="absolute top-2 right-2 bg-white rounded px-2 py-1 text-sm cursor-pointer hover:bg-red-100 shadow">
                                        <input type="checkbox" name="delete_images[]" value="{{ $imagePath }}" class="mr-1">
                                        <span class="text-red-600 font-semibold">🗑️</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-gray-600 text-sm mt-2">✓ Vink de afbeeldingen aan die je wilt verwijderen</p>
                    </div>
                @endif

                {{-- Nieuwe Afbeeldingen Uploaden --}}
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="new_images">
                        Nieuwe Afbeeldingen Toevoegen
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('new_images.*') border-red-500 @enderror"
                        id="new_images"
                        type="file"
                        name="new_images[]"
                        multiple
                        accept="image/*"
                    >
                    <p class="text-gray-600 text-sm mt-1">Je kunt meerdere afbeeldingen selecteren (JPEG, PNG, JPG, GIF, WEBP - max 5MB per afbeelding)</p>
                    @error('new_images.*')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-between pt-4 border-t">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition"
                        type="submit"
                    >
                        💾 Evenement Bijwerken
                    </button>
                    <a
                        href="{{ route('events.show', $event) }}"
                        class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800"
                    >
                        ← Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
