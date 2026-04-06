@props([
    'name' => 'Utilizator',
    'roleLabel' => null,
    'logoutRoute' => 'logout',
])

<details class="shell-profile-menu">
    <summary class="shell-profile-summary">
        <span class="shell-profile-name">{{ $name }}</span>
        @if ($roleLabel)
            <span class="shell-profile-role">{{ $roleLabel }}</span>
        @endif
    </summary>
    <div class="shell-profile-panel">
        <form method="POST" action="{{ route($logoutRoute) }}">
            @csrf
            <x-ui.button-secondary type="submit">Deconectare</x-ui.button-secondary>
        </form>
    </div>
</details>
