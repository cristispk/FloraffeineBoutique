@extends('layouts.auth')

@section('title', 'Panou comerciant')
@section('subtitle', 'Ești autentificat ca și comerciant.')

@section('content')
    <p style="font-size:0.9rem;margin-bottom:1rem;">
        Acesta este un panou provizoriu pentru zona de comerciant.
    </p>

    <form method="POST" action="{{ route('merchant.logout') }}">
        @csrf
        <button class="btn btn-secondary" type="submit">
            Deconectare
        </button>
    </form>
@endsection

