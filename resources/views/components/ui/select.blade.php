@props([
    'name',
    'id' => null,
    'options' => null,
])

@php
    $selectId = $id ?: $name;
    $baseClass = 'ds-input';
    $extraClass = $attributes->get('class');
    $class = trim($baseClass . ($extraClass ? ' ' . $extraClass : ''));

    // Expected minimal option shape:
    // [
    //   ['value' => '...', 'label' => '...'],
    //   ...
    // ]
@endphp

<select
    id="{{ $selectId }}"
    name="{{ $name }}"
    {{ $attributes->merge(['class' => $class]) }}
>
    @if (is_array($options))
        @foreach ($options as $opt)
            @php
                $value = $opt['value'] ?? null;
                $label = $opt['label'] ?? $value;
            @endphp
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
    @endif
</select>

