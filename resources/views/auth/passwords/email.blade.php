@extends('layouts.app')

@section('title', 'Password reset')

@section('content')
    <div class="max-w-md mx-auto my-12 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h1 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Reset your password</h1>

        @if (session('status'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="mb-4 text-sm text-gray-700 dark:text-gray-300">
            Enter the email of the account you want to change. No email will be sent — after submitting you will be redirected to set a new password immediately.
        </p>

        <form method="POST" action="{{ url('/password/email') }}">
            @csrf

            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-3 py-2 mb-4 border rounded bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-gray-100">

            <button type="submit"
                    class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Proceed to choose a new password
            </button>
        </form>
    </div>
@endsection
