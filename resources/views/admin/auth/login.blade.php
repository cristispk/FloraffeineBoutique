@extends('layouts.auth')

@section('title', 'Autentificare administrator')
@section('subtitle', 'Accesează zona de administrare Floraffeine Boutique.')

@section('form_action', url('/admin/login'))

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

@section('form_actions')
    <x-ui.button-primary type="submit">Autentificare</x-ui.button-primary>
@endsection

