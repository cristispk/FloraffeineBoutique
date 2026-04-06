@php($shell = 'admin')
@extends('layouts.base')

@section('body')
    <div class="shell-admin">
        @include('partials.shell.admin-sidebar')
        <div class="shell-admin-content">
            @include('partials.shell.admin-topbar')
            <main class="admin-main shell-admin-main">
                <x-ui.shell-page-container variant="admin" class="shell-workspace-surface shell-workspace-surface--admin">
                    <x-ui.shell-page-header :title="trim($__env->yieldContent('page_title')) !== '' ? $__env->yieldContent('page_title') : 'Panou administrator'">
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
