@extends('layouts.auth')

@section('title', 'Admin dashboard')
@section('subtitle', 'You are signed in as an administrator.')

@section('content')
    <p style="font-size:0.9rem;margin-bottom:1rem;">
        This is a placeholder dashboard for the admin area.
    </p>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-secondary" type="submit">
            Log out
        </button>
    </form>
@endsection

