@props([
    'title',
    'lead' => null,
    'image' => null,
])

<section {{ $attributes->merge(['class' => 'md-page-hero']) }}>
    @if($image)
        <div class="md-page-hero__media" aria-hidden="true">
            <img src="{{ asset($image) }}" alt="">
        </div>
        <div class="md-page-hero__overlay" aria-hidden="true"></div>
    @endif
    <div class="md-page-hero__content container">
        <p class="md-page-hero__brand">mydent</p>
        <h1 class="md-page-hero__title">{{ $title }}</h1>
        @if($lead)
            <p class="md-page-hero__lead">{{ $lead }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
