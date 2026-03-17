@extends('layouts.auth')

@section('title', 'Create your account')
@section('subtitle', 'Join Floraffeine Boutique as a shopper.')

@section('content')
    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div class="field">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <button class="btn" type="submit">
            Sign up
        </button>

        <div class="link-row">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}">Sign in</a>
        </div>
    </form>
@endsection

