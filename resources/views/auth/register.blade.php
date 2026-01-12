@extends('layouts.app')

@section('title', 'Register • Ticket Wave')

@section('content_full')
    <div class="auth-wrap">
        <div class="card auth-card">
            <div class="card-body">
                <div class="text-xl mb-4">Registreren</div>

                @if($errors->any())
                    <div class="mb-4" style="color:#fecaca;">
                        <ul style="margin:0; padding-left:18px;">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" style="display:grid; gap:14px;">
                    @csrf

                    <div>
                        <label>Naam</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label>Wachtwoord</label>
                        <input type="password" name="password" required>
                    </div>

                    <div>
                        <label>Bevestig wachtwoord</label>
                        <input type="password" name="password_confirmation" required>
                    </div>

                    <button class="btn btn-primary w-full" type="submit">Register</button>

                    <div class="muted" style="font-size:13px;">
                        Heb je al een account?
                        <a class="link" href="{{ route('login.show') }}">Log in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
