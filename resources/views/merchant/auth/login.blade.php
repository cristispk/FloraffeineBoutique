@extends('layouts.auth')

@section('title', 'Autentificare comerciant')
@section('subtitle', 'Accesează-ți panoul de creator.')

@section('form_action', url('/merchant/login'))

@section('form_fields')
    <div class="field">
        <label class="label" for="email">Email</label>
        <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />
        <x-ui.form-error name="email" />
    </div>

    <div class="field">
        <label class="label" for="password">Parolă</label>
        <x-ui.input id="password" name="password" type="password" required />
        <x-ui.form-error name="password" />
    </div>

    <div class="field">
        <label class="label" for="remember">
            <input
                id="remember"
                name="remember"
                type="checkbox"
                value="1"
                @checked(old('remember'))
            />
            Ține-mă minte
        </label>
    </div>
@endsection

@section('recaptcha')
    @if (config('recaptcha.enabled'))
        <div>
            <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
        </div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection

@section('form_actions')
    <x-ui.button-primary type="submit">Autentificare</x-ui.button-primary>
@endsection

@section('form_links')
    <a href="{{ route('merchant.password.request') }}">Ți-ai uitat parola?</a>
@endsection

