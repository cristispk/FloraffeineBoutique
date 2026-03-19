@props([
    'type' => 'button',
    'href' => null,
])

@php
    $baseClass = 'btn btn-dark rounded-pill px-4';
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $baseClass }}" {{ $attributes->except('class') }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $baseClass }}" {{ $attributes->except('class') }}>
        {{ $slot }}
    </button>
@endif

