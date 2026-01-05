@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto p-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <div class="flex flex-col lg:flex-row items-center gap-6">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Welcome</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">This is a simple starting page so the home view doesn't look empty.</p>

                        <div class="mt-4 flex flex-wrap gap-3">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded hover:bg-indigo-50 dark:hover:bg-gray-700">Register</a>
                                    @endif
                                @endauth
                            @endif

                            <a href="#" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded text-gray-800 dark:text-gray-200">Documentation</a>
                        </div>
                    </div>

                    <div class="w-44 h-44 bg-gradient-to-br from-pink-300 to-yellow-300 dark:from-pink-700 dark:to-yellow-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-semibold">Logo</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">Quick start</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Create your first project, add users and deploy.</p>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">Statistics</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Live metrics will appear here.</p>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">Support</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Find help and resources.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
