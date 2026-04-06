@php($shell = 'public')
@extends('layouts.base')

@section('body')
    @include('partials.shell.public-header')

    @hasSection('public_hero')
        <section class="shell-public-hero">
            @yield('public_hero')
        </section>
    @endif

    <main class="public-main shell-public-main">
        @yield('content')
    </main>

    @include('partials.shell.public-footer')
@endsection
