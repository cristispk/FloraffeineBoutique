@props([
    'variant' => 'public',
])

<div {{ $attributes->merge(['class' => "shell-page-container shell-page-container--{$variant}"]) }}>
    {{ $slot }}
</div>
