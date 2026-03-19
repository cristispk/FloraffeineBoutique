@props([
    'name',
    'type' => 'text',
    'id' => null,
    'value' => null,
])

@php
    $inputId = $id ?: $name;
    $hasError = $errors->has($name);
@endphp

@if ($type === 'checkbox')
    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="checkbox"
        @if ($value !== null) value="{{ $value }}" @endif
        {{ $attributes->except('class')->merge([
            'aria-invalid' => $hasError ? 'true' : null,
            'aria-describedby' => $hasError ? "error-{$name}" : null,
        ]) }}
    />
@else
    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($value !== null) value="{{ $value }}" @endif
        class="input"
        {{ $attributes->except('class')->merge([
            'aria-invalid' => $hasError ? 'true' : null,
            'aria-describedby' => $hasError ? "error-{$name}" : null,
        ]) }}
    />
@endif

