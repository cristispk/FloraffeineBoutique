@props([
    'class' => null,
])

@php
    $baseClass = 'form-card';
    $extraClass = $attributes->get('class');
    $mergedClass = trim($baseClass . ' ' . ($class ? $class : '') . ($extraClass ? ' ' . $extraClass : ''));
@endphp

<div {{ $attributes->merge(['class' => $mergedClass]) }}>
    @isset($header)
        <div>{{ $header }}</div>
    @endisset

    {{ $slot }}
</div>

