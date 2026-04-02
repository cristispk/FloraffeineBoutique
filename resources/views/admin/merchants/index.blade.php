@extends('layouts.auth')

@section('title', 'Comercianți')
@section('subtitle', 'Lista cererilor de verificare și a comercianților.')

@section('content')
    <p>
        <a href="{{ route('admin.merchants.index') }}">Doar în așteptare</a>
        |
        <a href="{{ route('admin.merchants.index', ['filter' => 'all']) }}">Toți comercianții</a>
    </p>

    @if ($merchants->isEmpty())
        <p>Nu există înregistrări de afișat.</p>
    @else
        <div class="table-responsive">
            <table class="table">
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
                            <td>{{ $merchant->id }}</td>
                            <td>{{ $merchant->business_name }}</td>
                            <td>{{ $merchant->user?->email }}</td>
                            <td>{{ $merchant->status->value }}</td>
                            <td>
                                <a href="{{ route('admin.merchants.show', $merchant) }}">Detalii</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $merchants->links() }}
    @endif

    <p class="mt-3">
        <a href="{{ route('admin.dashboard') }}">Înapoi la panou administrator</a>
    </p>
@endsection
