@props([
    'type' => 'info',
])

@php
    $class = match ($type) {
        'success' => 'alert-success',
        'danger', 'error' => 'alert-danger',
        'warning' => 'alert-warning',
        default => 'alert-info',
    };
@endphp

<div {{ $attributes->merge(['class' => "alert $class", 'role' => 'alert']) }}>
    {{ $slot }}
</div>
