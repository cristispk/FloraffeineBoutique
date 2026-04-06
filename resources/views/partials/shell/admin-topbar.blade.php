@php($user = auth()->user())
<header class="shell-admin-topbar">
    <div class="shell-topbar-inner shell-page-container shell-page-container--admin">
        <div class="shell-admin-topbar-left">
            @include('partials.shell.admin-mobile-nav')
            <div class="shell-admin-title">
                Spațiu administrare
            </div>
        </div>
        <div class="shell-admin-topbar-right">
            @hasSection('admin_tools')
                <div class="shell-admin-tools">@yield('admin_tools')</div>
            @endif
            <x-ui.topbar-profile-menu
                :name="$user?->name ?? 'Administrator'"
                role-label="Administrator"
                logout-route="admin.logout"
            />
        </div>
    </div>
</header>
