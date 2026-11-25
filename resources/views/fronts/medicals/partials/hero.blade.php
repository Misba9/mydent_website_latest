{{-- Brand-first full-bleed hero --}}
<section class="md-hero" aria-label="MyDent hero">
    <div class="md-hero__media" aria-hidden="true">
        <video autoplay muted loop playsinline poster="{{ asset('images/smile-with-aligners.jpg') }}">
            <source src="{{ asset('storage/vids/heroandchat.mp4') }}" type="video/mp4">
        </video>
    </div>
    <div class="md-hero__overlay" aria-hidden="true"></div>
    <div class="md-hero__content">
        <div class="container">
            <div class="md-hero-rise" style="max-width: 40rem;">
                <p class="md-hero__brand text-danger">mydent</p>
                <h1 class="md-hero__headline">
                    Your Dream Smile Starts Here.
                </h1>
                <p class="md-hero__support">
                    Discover the confidence of a naturally beautiful smile with MyDent Clear Aligners.
                    Comfortable, discreet and designed around you.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <x-front.button href="{{ route('medicalDoctors') }}">
                        Explore Clear Aligners
                    </x-front.button>
                    <a href="{{ route('medicalAppointment') }}" class="btn btn-outline-light" data-turbo="false">
                        Book a Consultation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
