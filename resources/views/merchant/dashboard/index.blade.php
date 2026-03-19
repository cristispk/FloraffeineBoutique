@extends('layouts.auth')

@section('title', 'Panou comerciant')
@section('subtitle', 'Ești autentificat ca și comerciant.')

@section('content')
    <p class="brand-serif">
        Acesta este un panou provizoriu pentru zona de comerciant.
    </p>

    <form method="POST" action="{{ route('merchant.logout') }}">
        @csrf
        <x-ui.button-secondary type="submit">
            Deconectare
        </x-ui.button-secondary>
    </form>
@endsection

