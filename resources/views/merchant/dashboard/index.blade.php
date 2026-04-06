@extends('layouts.app-shell')

@section('meta_title', 'Panou comerciant')
@section('page_title', 'Panou comerciant')

@section('content')
    <x-ui.section-block size="md">
        <x-ui.card>
            @include($statusPartial, ['merchant' => $merchant])
        </x-ui.card>
    </x-ui.section-block>
    <x-ui.section-block size="sm">
        <div class="shell-workspace-grid">
            <x-ui.card>
                <div class="brand-serif shell-card-title">Stare cont</div>
                <p class="shell-card-subtitle">Monitorizează progresul activării și pașii recomandați pentru următoarea etapă.</p>
            </x-ui.card>
            <x-ui.card>
                <div class="brand-serif shell-card-title">Acțiuni rapide</div>
                <p class="shell-card-subtitle">Finalizează datele profilului și verifică secțiunile pregătite pentru lansare.</p>
            </x-ui.card>
        </div>
    </x-ui.section-block>
@endsection
