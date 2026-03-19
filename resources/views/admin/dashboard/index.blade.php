@extends('layouts.auth')

@section('title', 'Panou administrator')
@section('subtitle', 'Ești autentificat ca administrator.')

@section('content')
    <p class="brand-serif">
        Acesta este un panou provizoriu pentru zona de administrator.
    </p>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <x-ui.button-secondary type="submit">
            Deconectare
        </x-ui.button-secondary>
    </form>
@endsection

