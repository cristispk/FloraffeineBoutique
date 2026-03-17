@extends('layouts.auth')

@section('title', 'Devino creator')
@section('subtitle', 'Înregistrează-te ca și comerciant pe Floraffeine Boutique.')

@section('content')
    <form method="POST" action="{{ url('/merchant/register') }}">
        @csrf

        <div class="field">
            <label for="name">Nume studio / brand</label>
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
            Înregistrează-te ca și comerciant
        </button>

        <div class="link-row">
            <span>Ești deja creator?</span>
            <a href="{{ route('merchant.login') }}">Autentificare comerciant</a>
        </div>
    </form>
    @if (config('recaptcha.enabled'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection

