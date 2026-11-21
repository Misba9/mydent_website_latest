@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
    'size' => null,
])

@php
    $classes = match ($variant) {
        'outline' => 'btn btn-outline-primary',
        'ghost' => 'btn btn-light',
        'light' => 'btn btn-light',
        default => 'btn btn-primary',
    };
    if ($size) {
        $classes .= ' btn-' . $size;
    }
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
