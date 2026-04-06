@extends('layouts.admin-shell')

@section('meta_title', 'Comercianți')
@section('page_title', 'Comercianți')
@section('breadcrumbs')
    <x-ui.breadcrumb :items="[['label' => 'Administrator', 'url' => route('admin.dashboard')]]" />
@endsection

@section('content')
    <x-ui.filter-bar>
        <div class="shell-filter-actions">
            <a href="{{ route('admin.merchants.index') }}">Doar în așteptare</a>
            <a href="{{ route('admin.merchants.index', ['filter' => 'all']) }}">Toți comercianții</a>
        </div>
    </x-ui.filter-bar>

    @if ($merchants->isEmpty())
        <x-ui.card>
            <p>Nu există înregistrări de afișat.</p>
        </x-ui.card>
    @else
        <x-ui.table-wrapper>
            <div class="table-responsive">
                <table class="table shell-admin-table--stacked">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Brand</th>
                            <th>Email</th>
                            <th>Stare</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($merchants as $merchant)
                            <tr>
                                <td data-label="ID">
                                    <span class="shell-admin-table__val">{{ $merchant->id }}</span>
                                </td>
                                <td data-label="Brand">
                                    <span class="shell-admin-table__val">{{ $merchant->business_name }}</span>
                                </td>
                                <td data-label="Email">
                                    <span class="shell-admin-table__val">{{ $merchant->user?->email }}</span>
                                </td>
                                <td data-label="Stare">
                                    <span class="shell-admin-table__val">{{ $merchant->status->value }}</span>
                                </td>
                                <td data-label="Acțiune" class="shell-admin-table__action">
                                    <a class="shell-link" href="{{ route('admin.merchants.show', $merchant) }}">Detalii</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $merchants->links() }}
        </x-ui.table-wrapper>
    @endif
@endsection
