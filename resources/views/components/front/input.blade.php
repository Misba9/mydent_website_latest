@props([
    'label' => null,
    'name',
    'type' => 'text',
    'error' => null,
])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-control' . ($error ? ' is-invalid' : '')]) }}
    />
    @if($error)
        <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
</div>
