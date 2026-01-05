<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
</head>
<body>
<h1>Register</h1>

@if($errors->any())
    <div style="color:red">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div>
        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
    </div>

    <div>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required>
    </div>

    <div>
        <button type="submit">Register</button>
    </div>
</form>

<p><a href="{{ route('login.show') }}">Login</a></p>
</body>
</html>
