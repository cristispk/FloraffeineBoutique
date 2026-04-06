@php($user = auth()->user())
<header class="shell-app-topbar">
    <div class="shell-topbar-inner shell-page-container shell-page-container--app">
        <div class="shell-app-topbar-left">
            @include('partials.shell.app-mobile-nav')
            <div class="shell-app-title">
                Spațiu aplicație
            </div>
        </div>
        <div class="shell-app-topbar-right">
            <x-ui.topbar-profile-menu
                :name="$user?->name ?? 'Utilizator'"
                :role-label="$user?->role === 'merchant' ? 'Comerciant' : 'Utilizator'"
                :logout-route="$user?->role === 'merchant' ? 'merchant.logout' : 'logout'"
            />
        </div>
    </div>
</header>
