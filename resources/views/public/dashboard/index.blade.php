@extends('layouts.auth')

@section('title', 'Panou utilizator')
@section('subtitle', 'Ești autentificat ca și client.')

@section('content')
    <p class="brand-serif">
        Acesta este un panou provizoriu pentru zona publică a utilizatorului.
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-ui.button-secondary type="submit">
            Deconectare
        </x-ui.button-secondary>
    </form>
@endsection

