@extends('layouts.auth')

@section('title', 'Panou administrator')
@section('subtitle', 'Ești autentificat ca administrator.')

@section('content')
    <p style="font-size:0.9rem;margin-bottom:1rem;">
        Acesta este un panou provizoriu pentru zona de administrator.
    </p>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-secondary" type="submit">
            Deconectare
        </button>
    </form>
@endsection

