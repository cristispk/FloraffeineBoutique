@extends('layouts.auth')

@section('title', 'Profil comerciant')
@section('subtitle', 'Completează datele afacerii tale înainte de trimiterea cererii de verificare.')

@section('form_action', route('merchant.onboarding.store'))

@section('form_fields')
    <div class="field">
        <label class="label" for="business_name">Nume studio / brand</label>
        <x-ui.input
            id="business_name"
            name="business_name"
            type="text"
            value="{{ old('business_name', $merchant->business_name) }}"
        />
        <x-ui.form-error name="business_name" />
    </div>

    <div class="field">
        <label class="label" for="description">Descriere scurtă</label>
        <textarea
            id="description"
            name="description"
            class="input"
            rows="4"
        >{{ old('description', $merchant->description) }}</textarea>
        <x-ui.form-error name="description" />
    </div>

    <div class="field">
        <label class="label" for="phone">Telefon</label>
        <x-ui.input
            id="phone"
            name="phone"
            type="text"
            value="{{ old('phone', $merchant->phone) }}"
        />
        <x-ui.form-error name="phone" />
    </div>

    <div class="field">
        <label class="label" for="city">Oraș</label>
        <x-ui.input
            id="city"
            name="city"
            type="text"
            value="{{ old('city', $merchant->city) }}"
        />
        <x-ui.form-error name="city" />
    </div>

    <div class="field">
        <label class="label" for="website">Site web (opțional)</label>
        <x-ui.input
            id="website"
            name="website"
            type="text"
            value="{{ old('website', $merchant->website) }}"
        />
        <x-ui.form-error name="website" />
    </div>

    @error('submit')
        <div role="alert">{{ $message }}</div>
    @enderror
@endsection

@section('form_actions')
    <x-ui.button-secondary type="submit" name="action" value="save">
        Salvează ciorna
    </x-ui.button-secondary>
    <x-ui.button-primary type="submit" name="action" value="submit">
        Trimite spre verificare
    </x-ui.button-primary>
@endsection

@section('form_links')
    <a href="{{ route('merchant.dashboard') }}">Înapoi la panou</a>
@endsection
