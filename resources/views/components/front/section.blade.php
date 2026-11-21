@props([
    'tone' => 'warm',
    'title' => null,
    'lead' => null,
    'eyebrow' => null,
    'centered' => true,
])

@php
    $toneClass = match ($tone) {
        'mint' => 'md-section--mint',
        'white' => 'md-section--white',
        'forest' => 'bg-primary text-white',
        default => 'md-section--warm',
    };
@endphp

<section {{ $attributes->merge(['class' => "md-section $toneClass md-reveal"]) }}>
    <div class="container">
        @if($eyebrow || $title || $lead)
            <div class="{{ $centered ? 'text-center' : '' }} mb-4">
                @if($eyebrow)
                    <p class="text-primary fw-semibold mb-2" style="letter-spacing: 0.04em; text-transform: uppercase; font-size: 0.85rem;">{{ $eyebrow }}</p>
                @endif
                @if($title)
                    <h2 class="md-section__title">{{ $title }}</h2>
                @endif
                @if($lead)
                    <p class="md-section__lead {{ $centered ? '' : 'ms-0' }}">{{ $lead }}</p>
                @endif
            </div>
        @endif
        {{ $slot }}
    </div>
</section>
