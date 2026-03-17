@extends('layouts.auth')

@section('title', 'Resetează parola de comerciant')
@section('subtitle', 'Îți vom trimite prin email un link sigur de resetare.')

@section('content')
    <form method="POST" action="{{ route('merchant.password.email') }}">
        @csrf

        <div class="field">
            <label for="email">Adresă de email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>

        <button class="btn" type="submit">
            Trimite link de resetare
        </button>

        <div class="link-row">
            <a href="{{ route('merchant.login') }}">Înapoi la autentificare comerciant</a>
        </div>
    </form>
@endsection

