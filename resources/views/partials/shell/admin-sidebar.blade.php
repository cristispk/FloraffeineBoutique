<aside class="shell-admin-sidebar">
    <div class="shell-sidebar-brand">
        <a class="brand" href="{{ route('admin.dashboard') }}">
            <x-ui.logo />
        </a>
        <div class="shell-sidebar-eyebrow">Spațiu administrare</div>
    </div>
    <nav aria-label="Navigație administrator" class="shell-sidebar-nav">
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
</aside>
