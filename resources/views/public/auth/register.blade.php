@extends('layouts.auth')

@section('title', 'Creează-ți contul')
@section('subtitle', 'Alătură-te platformei Floraffeine Boutique ca și client.')

@section('content')
    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div class="field">
            <label for="name">Nume</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
        </div>

        <div class="field">
            <label for="password">Parolă</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div class="field">
            <label for="password_confirmation">Confirmă parola</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        @if (config('recaptcha.enabled'))
            <div class="field">
                <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
            </div>
        @endif

        <button class="btn" type="submit">
            Creează cont
        </button>

        <div class="link-row">
            <span>Ai deja un cont?</span>
            <a href="{{ route('login') }}">Autentifică-te</a>
        </div>
    </form>
    @if (config('recaptcha.enabled'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection

