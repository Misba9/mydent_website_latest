{{-- Brand-first full-bleed hero: brand + headline + support + CTAs + media --}}
<section class="md-hero">
    <div class="md-hero__media" aria-hidden="true">
        <video autoplay muted loop playsinline poster="{{ asset('images/invisible.jpeg') }}">
            <source src="{{ asset('storage/vids/heroandchat.mp4') }}" type="video/mp4">
        </video>
    </div>
    <div class="md-hero__overlay" aria-hidden="true"></div>
    <div class="md-hero__content">
        <div class="container">
            <div class="md-hero-rise" style="max-width: 40rem;">
                <p class="md-hero__brand">mydent</p>
                <h1 class="md-hero__headline">
                    {{ $sliders->short_description ?? 'Invisible path to smile' }}
                </h1>
                <p class="md-hero__support">
                    {{ $sliders->title ?? 'Premium clear aligners with expert orthodontist supervision — start with a free 3D scan.' }}
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <x-front.button href="{{ route('medicalAppointment') }}" data-turbo="false">
                        Book a Scan
                    </x-front.button>
                    <a href="{{ route('medicalDoctors') }}" class="btn btn-outline-light">
                        Explore Aligners
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
