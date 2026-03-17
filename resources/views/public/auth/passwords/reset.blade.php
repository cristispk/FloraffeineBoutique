@extends('layouts.auth')

@section('title', 'Reset your password')
@section('subtitle', 'Choose a new password to secure your account.')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="field">
            <label for="password">New password</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <button class="btn" type="submit">
            Update password
        </button>

        <div class="link-row">
            <a href="{{ route('login') }}">Back to login</a>
        </div>
    </form>
@endsection

