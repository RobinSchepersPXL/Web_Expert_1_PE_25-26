@extends('layouts.app')

@section('title', 'Log in • Ticket Wave')

@section('content_full')
    <div class="min-h-[calc(100vh-120px)] w-full flex items-center justify-center px-5 py-10">
        <div class="w-full max-w-md card">
            <div class="card-body">
                <h1 class="text-2xl font-extrabold tracking-tight mb-4">Inloggen</h1>

                @if(session('status'))
                    <div class="mb-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-emerald-100">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-red-100">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-slate-200">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-slate-200">Wachtwoord</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-slate-300">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-white/10">
                            Onthoud mij
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="underline text-blue-300 hover:text-blue-200">
                                Wachtwoord vergeten?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-full justify-center py-2.5">
                        Inloggen
                    </button>
                </form>

                <div class="mt-5 text-sm text-slate-300">
                    Nog geen account?
                    <a href="{{ route('register.show') }}" class="underline text-blue-300 hover:text-blue-200">
                        Registreer hier
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
