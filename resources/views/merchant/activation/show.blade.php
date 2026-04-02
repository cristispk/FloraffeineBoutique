@extends('layouts.auth')

@section('title', 'Activare cont creator')
@section('subtitle', 'Confirmă că ești gata să îți folosești contul pe Floraffeine Boutique.')

@section('content')
    <p class="brand-serif">
        Contul tău a fost aprobat. Următorul pas este activarea oficială ca și creator.
    </p>
    <p>
        Facturarea planului Creator și plățile vor fi disponibile într-o versiune viitoare a platformei.
        Nu îți vom cere date de plată în această etapă.
    </p>
    <p>
        Prin confirmare, îți asumi că vei respecta regulile platformei și că datele din profil sunt corecte.
    </p>

    <form method="POST" action="{{ route('merchant.activation.confirm') }}" class="mt-3">
        @csrf
        <x-ui.button-primary type="submit">
            Confirm activarea contului creator
        </x-ui.button-primary>
    </form>

    <div class="mt-3">
        <a href="{{ route('merchant.dashboard') }}">Înapoi la panou</a>
    </div>
@endsection
