@props([
    'title' => 'Panou',
    'subtitle' => null,
])

<header {{ $attributes->merge(['class' => 'shell-page-header']) }}>
    <div class="shell-page-header-main">
        @isset($breadcrumbs)
            <div class="shell-page-header-breadcrumbs">{{ $breadcrumbs }}</div>
        @endisset
        <h1 class="shell-page-header-title">{{ $title }}</h1>
        @if ($subtitle)
            <p class="shell-page-header-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="shell-page-header-actions">{{ $actions }}</div>
    @endisset
</header>
