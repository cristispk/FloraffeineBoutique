<details class="shell-admin-mobile-nav d-lg-none">
    <summary class="shell-mobile-nav-toggle">
        <x-ui.mobile-drawer-toggle label="Navigație admin" />
    </summary>
    <div class="shell-mobile-nav-panel">
        <nav aria-label="Navigație admin mobilă" class="shell-mobile-nav-links">
            <x-ui.sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('admin.merchants.index')" :active="request()->routeIs('admin.merchants.*')">Comercianți</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Utilizatori</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Produse</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Comenzi</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Evenimente</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Promoții</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Abonamente</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="null" :disabled="true">Setări</x-ui.sidebar-link>
        </nav>
    </div>
</details>
