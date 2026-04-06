@props([
    'size' => 'md',
])

<section {{ $attributes->merge(['class' => "shell-section-block shell-section-block--{$size}"]) }}>
    {{ $slot }}
</section>
