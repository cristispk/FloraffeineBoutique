@extends('layouts.auth')

@section('title', 'Reset merchant password')
@section('subtitle', 'We will email you a secure reset link.')

@section('content')
    <form method="POST" action="{{ route('merchant.password.email') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>

        <button class="btn" type="submit">
            Send reset link
        </button>

        <div class="link-row">
            <a href="{{ route('merchant.login') }}">Back to merchant login</a>
        </div>
    </form>
@endsection

