@php($shell = 'auth')
@extends('layouts.base')

@section('meta_title')
    {{ config('app.name', 'Floraffeine Boutique') }} - Autentificare
@endsection

@section('body')
    <header class="site-header shell-auth-header">
        <div class="nav-glass">
            <div class="nav-inner">
                <div class="d-flex align-items-center gap-2">
                    <a class="brand" href="{{ url('/') }}" aria-label="Acasă Floraffeine Boutique">
                        <x-ui.logo />
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="ai-form-section shell-auth-main">
        @hasSection('form_action')
            <form class="ai-form" action="@yield('form_action')" method="post">
                @csrf

                <x-ui.card>
                    @hasSection('title')
                        <div class="brand-serif">
                            @yield('title')
                        </div>
                    @else
                        <div class="brand-serif">{{ config('app.name', 'Floraffeine Boutique') }}</div>
                    @endif

                    @hasSection('subtitle')
                        <div>@yield('subtitle')</div>
                    @endif

                    @if (session('status'))
                        <x-ui.alert>{{ session('status') }}</x-ui.alert>
                    @endif

                    @if ($errors->any())
                        <x-ui.alert variant="error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-ui.alert>
                    @endif

                    <div class="form-grid">
                        @yield('form_fields')
                    </div>

                    @yield('recaptcha')

                    <div class="form-actions">
                        @yield('form_actions')
                    </div>

                    @yield('form_links')
                </x-ui.card>
            </form>
        @else
            <div class="ai-form">
                <x-ui.card>
                    @hasSection('title')
                        <div class="brand-serif">
                            @yield('title')
                        </div>
                    @else
                        <div class="brand-serif">{{ config('app.name', 'Floraffeine Boutique') }}</div>
                    @endif

                    @hasSection('subtitle')
                        <div>@yield('subtitle')</div>
                    @endif

                    @if (session('status'))
                        <x-ui.alert>{{ session('status') }}</x-ui.alert>
                    @endif

                    @if ($errors->any())
                        <x-ui.alert variant="error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-ui.alert>
                    @endif

                    @yield('content')
                </x-ui.card>
            </div>
        @endif
    </div>
@endsection

