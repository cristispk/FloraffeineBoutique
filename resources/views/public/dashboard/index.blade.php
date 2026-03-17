@extends('layouts.auth')

@section('title', 'User dashboard')
@section('subtitle', 'You are signed in as a shopper.')

@section('content')
    <p style="font-size:0.9rem;margin-bottom:1rem;">
        This is a placeholder dashboard for the public user area.
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-secondary" type="submit">
            Log out
        </button>
    </form>
@endsection

