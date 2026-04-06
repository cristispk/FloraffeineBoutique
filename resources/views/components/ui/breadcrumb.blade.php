@props([
    'items' => [],
])

<nav class="shell-breadcrumb" aria-label="Fil de navigare">
    @foreach ($items as $item)
        @php
            $isLast = $loop->last;
            $label = $item['label'] ?? '';
            $url = $item['url'] ?? null;
        @endphp
        @if ($url)
            <a href="{{ $url }}" class="shell-breadcrumb-link">{{ $label }}</a>
            @if (!$isLast)
                <span class="shell-breadcrumb-sep">/</span>
            @endif
        @else
            <span class="shell-breadcrumb-current">{{ $label }}</span>
        @endif
    @endforeach
</nav>
