<details class="shell-app-mobile-nav d-lg-none">
    <summary class="shell-mobile-nav-toggle">
        <x-ui.mobile-drawer-toggle label="Navigație" />
    </summary>
    <div class="shell-mobile-nav-panel">
        <nav aria-label="Navigație aplicație mobilă" class="shell-mobile-nav-links">
            @php($user = auth()->user())
            <x-ui.sidebar-link :href="$user?->role === 'merchant' ? route('merchant.dashboard') : route('user.dashboard')" :active="$user?->role === 'merchant' ? request()->routeIs('merchant.dashboard') : request()->routeIs('user.dashboard')">
                Panou de control
            </x-ui.sidebar-link>
            @if ($user?->role === 'merchant')
                <x-ui.sidebar-link :href="Route::has('merchant.onboarding') ? route('merchant.onboarding') : null" :disabled="!Route::has('merchant.onboarding')">Profil comerciant</x-ui.sidebar-link>
                <x-ui.sidebar-link :href="null" :disabled="true">Produse</x-ui.sidebar-link>
                <x-ui.sidebar-link :href="null" :disabled="true">Cereri / Comenzi</x-ui.sidebar-link>
                <x-ui.sidebar-link :href="null" :disabled="true">Evenimente</x-ui.sidebar-link>
                <x-ui.sidebar-link :href="Route::has('merchant.activation') ? route('merchant.activation') : null" :disabled="!Route::has('merchant.activation')">Abonament</x-ui.sidebar-link>
                <x-ui.sidebar-link :href="null" :disabled="true">Setări</x-ui.sidebar-link>
            @endif
        </nav>
    </div>
</details>
