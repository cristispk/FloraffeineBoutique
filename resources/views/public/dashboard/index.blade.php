@extends('layouts.app-shell')

@section('meta_title', 'Panou utilizator')
@section('page_title', 'Panou utilizator')

@section('content')
    <x-ui.section-block size="md">
        <x-ui.card>
            <p class="brand-serif">
                Acesta este un panou provizoriu pentru zona utilizatorului autentificat.
            </p>
        </x-ui.card>
    </x-ui.section-block>
    <x-ui.section-block size="sm">
        <div class="shell-workspace-grid">
            <x-ui.card>
                <div class="brand-serif shell-card-title">Recomandări</div>
                <p class="shell-card-subtitle">Explorează secțiunile noului spațiu pentru utilizatori autentificați.</p>
            </x-ui.card>
            <x-ui.card>
                <div class="brand-serif shell-card-title">Activitate cont</div>
                <p class="shell-card-subtitle">În curând vei găsi aici un rezumat al acțiunilor importante.</p>
            </x-ui.card>
        </div>
    </x-ui.section-block>
@endsection

