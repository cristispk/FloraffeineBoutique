@extends('layouts.auth')

@section('title', 'Become a creator')
@section('subtitle', 'Register as a Floraffeine Boutique merchant.')

@section('content')
    <form method="POST" action="{{ url('/merchant/register') }}">
        @csrf

        <div class="field">
            <label for="name">Studio / brand name</label>
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
            Register as merchant
        </button>

        <div class="link-row">
            <span>Already a creator?</span>
            <a href="{{ route('merchant.login') }}">Merchant login</a>
        </div>
    </form>
@endsection

