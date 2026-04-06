<header class="site-header shell-public-header">
    <div class="nav-glass">
        <div class="nav-inner">
            <a class="brand" href="{{ url('/') }}" aria-label="Acasă Floraffeine Boutique">
                <x-ui.logo />
            </a>

            <nav class="shell-public-nav" aria-label="Navigație publică">
                <x-ui.nav-link :href="url('/')" :active="request()->routeIs('home') || request()->path() === '/'">Acasă</x-ui.nav-link>
                <x-ui.nav-link :href="Route::has('boutique.about') ? route('boutique.about') : null" :disabled="!Route::has('boutique.about')">Despre Boutique</x-ui.nav-link>
                <x-ui.nav-link :href="Route::has('boutique.creators') ? route('boutique.creators') : null" :disabled="!Route::has('boutique.creators')">Creatori / Comercianți</x-ui.nav-link>
                <x-ui.nav-link :href="Route::has('boutique.events') ? route('boutique.events') : null" :disabled="!Route::has('boutique.events')">Evenimente / Showcase</x-ui.nav-link>
                <x-ui.nav-link :href="Route::has('boutique.how') ? route('boutique.how') : null" :disabled="!Route::has('boutique.how')">Cum funcționează</x-ui.nav-link>
            </nav>

            <div class="shell-public-cta">
                <x-ui.button-secondary :href="Route::has('login') ? route('login') : null" :disabled="!Route::has('login')">Autentificare</x-ui.button-secondary>
                <x-ui.button-primary :href="Route::has('register') ? route('register') : null" :disabled="!Route::has('register')">Creează cont</x-ui.button-primary>
            </div>

            @include('partials.shell.public-mobile-nav')
        </div>
    </div>
</header>
