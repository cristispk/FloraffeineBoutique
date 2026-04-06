@php
    $shell = 'app';
    $user = auth()->user();
    $appRole = $user?->role;
@endphp
@extends('layouts.base')

@section('body')
    <div class="shell-app">
        @include('partials.shell.app-sidebar')
        <div class="shell-app-content">
            @include('partials.shell.app-topbar')
            <main class="app-main shell-app-main">
                <x-ui.shell-page-container variant="app" class="shell-workspace-surface shell-workspace-surface--app">
                    <x-ui.shell-page-header :title="trim($__env->yieldContent('page_title')) !== '' ? $__env->yieldContent('page_title') : 'Panou de control'">
                        @hasSection('breadcrumbs')
                            <x-slot:breadcrumbs>
                                @yield('breadcrumbs')
                            </x-slot:breadcrumbs>
                        @endif
                        @hasSection('page_actions')
                            <x-slot:actions>
                                @yield('page_actions')
                            </x-slot:actions>
                        @endif
                    </x-ui.shell-page-header>
                    @yield('content')
                </x-ui.shell-page-container>
            </main>
        </div>
    </div>
@endsection
