@extends('layouts.admin-shell')

@section('meta_title', 'Detalii comerciant')
@section('page_title', "Comerciant #{$merchant->id}")
@section('breadcrumbs')
    <x-ui.breadcrumb :items="[
        ['label' => 'Administrator', 'url' => route('admin.dashboard')],
        ['label' => 'Comercianți', 'url' => route('admin.merchants.index')],
    ]" />
@endsection

@section('content')
    <x-ui.section-block size="md">
        <x-ui.card>
            <p><strong>Nume brand:</strong> {{ $merchant->business_name }}</p>
            <p><strong>Email:</strong> {{ $merchant->user?->email }}</p>
            <p><strong>Stare:</strong> {{ $merchant->status->value }}</p>
            @if ($merchant->description)
                <p><strong>Descriere:</strong> {{ $merchant->description }}</p>
            @endif
            @if ($merchant->phone)
                <p><strong>Telefon:</strong> {{ $merchant->phone }}</p>
            @endif
            @if ($merchant->city)
                <p><strong>Oraș:</strong> {{ $merchant->city }}</p>
            @endif
            @if ($merchant->website)
                <p><strong>Site:</strong> {{ $merchant->website }}</p>
            @endif
            @if ($merchant->submitted_at)
                <p><strong>Trimis la:</strong> {{ $merchant->submitted_at->format('d.m.Y H:i') }}</p>
            @endif
            @if ($merchant->rejection_reason)
                <p><strong>Motiv respingere:</strong> {{ $merchant->rejection_reason }}</p>
            @endif
            @if ($merchant->suspension_reason)
                <p><strong>Motiv suspendare:</strong> {{ $merchant->suspension_reason }}</p>
            @endif
        </x-ui.card>
    </x-ui.section-block>

    <x-ui.section-block size="sm">
        @if ($merchant->status === \App\Enums\MerchantStatus::PendingReview)
            <form method="POST" action="{{ route('admin.merchants.approve', $merchant) }}" class="mt-3">
                @csrf
                <x-ui.button-primary type="submit">Aprobă</x-ui.button-primary>
            </form>

            <form method="POST" action="{{ route('admin.merchants.reject', $merchant) }}" class="mt-3">
                @csrf
                <div class="field">
                    <label class="label" for="rejection_reason">Motiv respingere</label>
                    <textarea id="rejection_reason" name="rejection_reason" class="input" rows="3" required>{{ old('rejection_reason') }}</textarea>
                    <x-ui.form-error name="rejection_reason" />
                </div>
                <x-ui.button-secondary type="submit">Respinge</x-ui.button-secondary>
            </form>
        @endif

        @if ($merchant->status === \App\Enums\MerchantStatus::Active)
            <form method="POST" action="{{ route('admin.merchants.suspend', $merchant) }}" class="mt-3">
                @csrf
                <div class="field">
                    <label class="label" for="suspension_reason">Motiv suspendare</label>
                    <textarea id="suspension_reason" name="suspension_reason" class="input" rows="3" required>{{ old('suspension_reason') }}</textarea>
                    <x-ui.form-error name="suspension_reason" />
                </div>
                <x-ui.button-secondary type="submit">Suspendă</x-ui.button-secondary>
            </form>
        @endif

        @if ($merchant->status === \App\Enums\MerchantStatus::Suspended)
            <form method="POST" action="{{ route('admin.merchants.reactivate', $merchant) }}" class="mt-3">
                @csrf
                <x-ui.button-primary type="submit">Reactivează</x-ui.button-primary>
            </form>
        @endif
    </x-ui.section-block>
@endsection
