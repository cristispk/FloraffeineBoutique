<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Floraffeine Boutique') }} - Autentificare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap (rounded-pill/px-*) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Always load intended CSS (works without Vite build artifacts) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
<header class="site-header">
    <div class="nav-glass">
        <div class="nav-inner">
            <div class="d-flex align-items-center gap-2">
                <a class="brand" href="{{ url('/') }}" aria-label="Floraffeine Couture home">
                    <x-ui.logo />
                </a>
            </div>
        </div>
    </div>
</header>

<div class="ai-form-section">
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
                    <div role="alert">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
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
                    <div role="alert">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </x-ui.card>
        </div>
    @endif
</div>
</body>
</html>

