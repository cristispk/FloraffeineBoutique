@extends('layouts.auth')

@section('title', 'Autentificare comerciant')
@section('subtitle', 'Accesează-ți panoul de creator.')

@section('content')
    <form method="POST" action="{{ url('/merchant/login') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="field">
            <label for="password">Parolă</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div class="field" style="display:flex;align-items:center;gap:0.4rem;">
            <input id="remember" name="remember" type="checkbox" value="1" style="width:auto;">
            <label for="remember" style="margin:0;">Ține-mă minte</label>
        </div>

        @if (config('recaptcha.enabled'))
            <div class="field">
                <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
            </div>
        @endif

        <button class="btn" type="submit">
            Autentificare
        </button>

        <div class="link-row">
            <a href="{{ route('merchant.password.request') }}">Ți-ai uitat parola?</a>
        </div>
    </form>
    @if (config('recaptcha.enabled'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection

