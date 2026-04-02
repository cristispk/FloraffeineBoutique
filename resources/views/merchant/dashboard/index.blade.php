@extends('layouts.auth')

@section('title', 'Panou comerciant')
@section('subtitle', 'Zona ta de creator pe Floraffeine Boutique.')

@section('content')
    @include($statusPartial, ['merchant' => $merchant])

    <form method="POST" action="{{ route('merchant.logout') }}" class="mt-4">
        @csrf
        <x-ui.button-secondary type="submit">
            Deconectare
        </x-ui.button-secondary>
    </form>
@endsection
