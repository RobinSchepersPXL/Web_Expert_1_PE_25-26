@extends('layouts.app')

@section('title', 'Inloggen • Ticket Wave')

@section('content_full')
    <div class="auth-wrap">
        <div class="card auth-card">
            <div class="card-body">
                <div class="text-xl mb-4">Inloggen</div>

                @if(session('status'))
                    <div class="mb-4 muted">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="mb-4" style="color:#fecaca;">
                        <ul style="margin:0; padding-left:18px;">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" style="display:grid; gap:14px;">
                    @csrf

                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div>
                        <label>Wachtwoord</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="flex justify-between items-center" style="font-size:13px;">
                        <label class="muted" style="display:flex; align-items:center; gap:8px; margin:0;">
                            <input type="checkbox" name="remember" style="width:auto;">
                            Onthoud mij
                        </label>

                        @if(Route::has('password.request'))
                            <a class="link" href="{{ route('password.request') }}">Wachtwoord vergeten?</a>
                        @endif
                    </div>

                    <button class="btn btn-primary w-full" type="submit">Inloggen</button>

                    <div class="muted" style="font-size:13px;">
                        Nog geen account?
                        <a class="link" href="{{ route('register.show') }}">Registreer hier</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

