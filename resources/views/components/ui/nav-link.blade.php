@props([
    'href' => null,
    'active' => false,
    'disabled' => false,
])

@php
    $class = 'shell-nav-link';
    if ($active) {
        $class .= ' is-active';
    }
    if ($disabled || !$href) {
        $class .= ' is-disabled';
    }
@endphp

@if ($disabled || !$href)
    <span {{ $attributes->merge(['class' => $class]) }} aria-disabled="true">{{ $slot }}</span>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@endif
