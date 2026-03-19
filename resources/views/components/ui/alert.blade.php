@props([
    'variant' => 'success',
])

@php
    $baseClass = 'ds-alert';
    $variantClass = $variant === 'error' ? 'ds-alert--error' : 'ds-alert--success';
    $extraClass = $attributes->get('class');
    $class = trim($baseClass . ' ' . $variantClass . ($extraClass ? ' ' . $extraClass : ''));
@endphp

<div {{ $attributes->merge(['class' => $class, 'role' => 'alert']) }}>
    {{ $slot }}
</div>

