@extends('layouts.auth')

@section('title', 'Ți-ai uitat parola?')
@section('subtitle', 'Îți vom trimite prin email un link sigur de resetare.')

@section('content')
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label for="email">Adresă de email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>

        <button class="btn" type="submit">
            Trimite link de resetare
        </button>

        <div class="link-row">
            <a href="{{ route('login') }}">Înapoi la autentificare</a>
        </div>
    </form>
@endsection

