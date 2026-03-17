@extends('layouts.auth')

@section('title', 'Panou utilizator')
@section('subtitle', 'Ești autentificat ca și client.')

@section('content')
    <p style="font-size:0.9rem;margin-bottom:1rem;">
        Acesta este un panou provizoriu pentru zona publică a utilizatorului.
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-secondary" type="submit">
            Deconectare
        </button>
    </form>
@endsection

