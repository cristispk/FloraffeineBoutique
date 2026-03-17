@extends('layouts.auth')

@section('title', 'Admin login')
@section('subtitle', 'Access the Floraffeine Boutique admin area.')

@section('content')
    <form method="POST" action="{{ url('/admin/login') }}">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div class="field" style="display:flex;align-items:center;gap:0.4rem;">
            <input id="remember" name="remember" type="checkbox" value="1" style="width:auto;">
            <label for="remember" style="margin:0;">Remember me</label>
        </div>

        <button class="btn" type="submit">
            Sign in
        </button>
    </form>
@endsection

