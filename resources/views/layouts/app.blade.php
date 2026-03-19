<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Floraffeine Boutique') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap (utilities + rounded-pill/px-*) -->
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

            @hasSection('header_nav')
                <nav aria-label="Navigație principală">
                    @yield('header_nav')
                </nav>
            @endif
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer>
    <div>© {{ date('Y') }} Floraffeine Boutique</div>
</footer>
</body>
</html>

