@extends('layouts.app')

@section('content')
    <div class="ai-form-section">
        <div class="ai-form">
            <x-ui.card>
                <div class="brand-serif">Bine ai venit la Floraffeine Boutique</div>
                <div>Autentifică-te sau creează un cont pentru a începe.</div>

                <div class="form-actions">
                    <x-ui.button-primary href="{{ route('login') }}">Autentificare</x-ui.button-primary>
                    <x-ui.button-secondary href="{{ route('register') }}">Creează cont</x-ui.button-secondary>
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection

