@props([
    'title' => 'Nothing here yet',
    'message' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-5']) }}>
    <h3 class="h5 mb-2" style="font-family: var(--md-font-display);">{{ $title }}</h3>
    @if($message)
        <p class="text-muted mb-0">{{ $message }}</p>
    @endif
    {{ $slot }}
</div>
