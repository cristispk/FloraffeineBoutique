@php($user = auth()->user())
<aside class="shell-app-sidebar">
    <div class="shell-sidebar-brand">
        <a class="brand" href="{{ $user?->role === 'merchant' ? route('merchant.dashboard') : route('user.dashboard') }}">
            <x-ui.logo />
        </a>
        <div class="shell-sidebar-eyebrow">Spațiu aplicație</div>
    </div>

    <nav aria-label="Navigație aplicație" class="shell-sidebar-nav">
        <x-ui.sidebar-link :href="$user?->role === 'merchant' ? route('merchant.dashboard') : route('user.dashboard')" :active="$user?->role === 'merchant' ? request()->routeIs('merchant.dashboard') : request()->routeIs('user.dashboard')">
            Panou de control
        </x-ui.sidebar-link>

        @if ($user?->role === 'merchant')
            <x-ui.sidebar-link :href="Route::has('merchant.onboarding') ? route('merchant.onboarding') : null" :disabled="!Route::has('merchant.onboarding')" :active="request()->routeIs('merchant.onboarding*')">
                Profil comerciant
            </x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Produse</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Cereri / Comenzi</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Evenimente</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="Route::has('merchant.activation') ? route('merchant.activation') : null" :disabled="!Route::has('merchant.activation')" :active="request()->routeIs('merchant.activation*')">
                Abonament
            </x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Setări</x-ui.sidebar-link>
        @else
            <x-ui.sidebar-link :href="null" :disabled="true">Profil</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Date cont</x-ui.sidebar-link>
        @endif
    </nav>

</aside>
