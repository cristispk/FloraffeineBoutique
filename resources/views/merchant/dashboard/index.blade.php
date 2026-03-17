@extends('layouts.auth')

@section('title', 'Merchant dashboard')
@section('subtitle', 'You are signed in as a merchant.')

@section('content')
    <p style="font-size:0.9rem;margin-bottom:1rem;">
        This is a placeholder dashboard for the merchant area.
    </p>

    <form method="POST" action="{{ route('merchant.logout') }}">
        @csrf
        <button class="btn btn-secondary" type="submit">
            Log out
        </button>
    </form>
@endsection

