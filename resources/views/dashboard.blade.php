@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <main class="max-w-7xl mx-auto px-6 py-16 lg:flex lg:items-center lg:gap-x-20">
            <div class="w-full lg:w-1/2">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white">
                    Dashboard
                </h1>
                <p class="mt-6 text-base text-gray-600 dark:text-gray-300 max-w-xl">
                    Overzicht van jouw gegevens en snelle toegang tot belangrijke acties.
                </p>

                {{-- Voeg hier eventuele dashboard-widgets, knoppen of kaarten toe --}}
                <div class="mt-8 grid gap-6 sm:grid-cols-2">
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Statistiek 1</h3>
                        <p class="mt-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">42</p>
                    </div>
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Statistiek 2</h3>
                        <p class="mt-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">128</p>
                    </div>
                </div>
            </div>


        </main>
    </div>
@endsection
