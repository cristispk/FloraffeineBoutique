@props([
    'name',
])

@php
    $message = $errors->first($name);
@endphp

@if ($message)
    <div id="error-{{ $name }}" role="alert">
        {{ $message }}
    </div>
@endif

