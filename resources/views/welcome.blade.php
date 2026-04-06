@extends('layouts.app')

@section('meta_title', 'Floraffeine Boutique')

@section('public_hero')
    <x-ui.shell-page-container variant="public">
        <x-ui.section-block size="lg">
            <div class="shell-hero-content">
                <div class="brand-serif shell-hero-title">Floraffeine Boutique</div>
                <p class="shell-hero-subtitle">Descoperă creații autentice într-un spațiu premium.</p>
                <div class="shell-hero-badge">Selecție curatoriată pentru iubitorii de design floral și lifestyle.</div>
            </div>
        </x-ui.section-block>
    </x-ui.shell-page-container>
@endsection

@section('content')
    <x-ui.shell-page-container variant="public">
        <x-ui.section-block size="md">
            <x-ui.card>
                <div class="brand-serif shell-card-title">Acces la contul tău</div>
                <div class="shell-card-subtitle">Autentifică-te sau creează un cont pentru a explora spațiul Boutique.</div>
                <div class="form-actions shell-card-actions">
                    <x-ui.button-primary href="{{ route('login') }}">Autentificare</x-ui.button-primary>
                    <x-ui.button-secondary href="{{ route('register') }}">Creează cont</x-ui.button-secondary>
                </div>
            </x-ui.card>
        </x-ui.section-block>

        <x-ui.section-block size="sm">
            <div class="shell-public-grid">
                <x-ui.card>
                    <div class="brand-serif shell-card-title">Experiență premium</div>
                    <p class="shell-card-subtitle">Navigare clară, conținut aerisit și prezentare orientată spre brand.</p>
                </x-ui.card>
                <x-ui.card>
                    <div class="brand-serif shell-card-title">Spațiu pentru creatori</div>
                    <p class="shell-card-subtitle">Un ecosistem elegant, pregătit pentru comercianți și colecții viitoare.</p>
                </x-ui.card>
            </div>
        </x-ui.section-block>
    </x-ui.shell-page-container>
@endsection

