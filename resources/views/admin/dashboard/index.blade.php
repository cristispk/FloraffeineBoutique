@extends('layouts.admin-shell')

@section('meta_title', 'Panou administrator')
@section('page_title', 'Panou administrator')

@section('content')
    <x-ui.section-block size="md">
        <x-ui.card>
            <p class="brand-serif">
                Acesta este un panou provizoriu pentru zona de administrator.
            </p>
        </x-ui.card>
    </x-ui.section-block>
    <x-ui.section-block size="sm">
        <div class="shell-workspace-grid shell-workspace-grid--admin">
            <x-ui.card>
                <div class="brand-serif shell-card-title">Control operațional</div>
                <p class="shell-card-subtitle">Accesează rapid secțiunile administrative și urmărește elementele în așteptare.</p>
            </x-ui.card>
            <x-ui.card>
                <div class="brand-serif shell-card-title">Revizuire comercianți</div>
                <p class="shell-card-subtitle">Zona de comercianți este pregătită pentru filtrare, listare și acțiuni de verificare.</p>
            </x-ui.card>
        </div>
    </x-ui.section-block>
@endsection

