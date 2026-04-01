@extends('layouts.auth')

@section('title', 'Setează o parolă nouă')
@section('subtitle', 'Actualizează parola contului tău de comerciant.')

@section('content')
    <form method="POST" action="{{ route('merchant.password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label for="email">Adresă de email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="field">
            <label for="password">Parolă nouă</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div class="field">
            <label for="password_confirmation">Confirmă parola nouă</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <button class="btn" type="submit">
            Actualizează parola
        </button>

        <div class="link-row">
            <a href="{{ route('login') }}">Înapoi la autentificare</a>
        </div>
    </form>
@endsection

