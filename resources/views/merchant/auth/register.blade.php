@extends('layouts.auth')

@section('title', 'Devino creator')
@section('subtitle', 'Înregistrează-te ca și comerciant pe Floraffeine Boutique.')

@section('form_action', url('/merchant/register'))

@section('form_fields')
    <div class="field">
        <label class="label" for="name">Nume studio / brand</label>
        <x-ui.input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus />
        <x-ui.form-error name="name" />
    </div>

    <div class="field">
        <label class="label" for="email">Email</label>
        <x-ui.input id="email" name="email" type="email" value="{{ old('email') }}" required />
        <x-ui.form-error name="email" />
    </div>

    <div class="field">
        <label class="label" for="password">Parolă</label>
        <x-ui.input id="password" name="password" type="password" required />
        <x-ui.form-error name="password" />
    </div>

    <div class="field">
        <label class="label" for="password_confirmation">Confirmă parola</label>
        <x-ui.input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            required
        />
        <x-ui.form-error name="password_confirmation" />
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
    <x-ui.button-primary type="submit">Înregistrează-te ca și comerciant</x-ui.button-primary>
@endsection

@section('form_links')
    <span>Ești deja creator?</span>
    <a href="{{ route('merchant.login') }}">Autentificare comerciant</a>
@endsection

