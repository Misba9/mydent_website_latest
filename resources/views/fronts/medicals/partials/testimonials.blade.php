<section class="md-section md-section--white md-reveal" aria-labelledby="md-testimonials-title">
    <div class="container">
        <div class="text-center mb-4">
            <span class="md-section__eyebrow">Testimonials</span>
            <h2 id="md-testimonials-title" class="md-section__title">What patients say</h2>
            <p class="md-section__lead mx-auto">Verified reviews from the MyDent website.</p>
        </div>

        @if(isset($testimonials) && $testimonials->count())
            <div class="row g-4">
                @foreach($testimonials as $testimonial)
                    <div class="col-md-6 col-lg-4">
                        <article class="md-benefit-card h-100">
                            <img src="{{ $testimonial->front_patient_profile }}"
                                 alt="{{ $testimonial->name }}"
                                 width="56"
                                 height="56"
                                 class="rounded-circle mb-3"
                                 style="object-fit: cover;"
                                 loading="lazy">
                            <p class="mb-3">“{{ $testimonial->short_description }}”</p>
                            <h3 class="h6 mb-0">{{ $testimonial->name }}</h3>
                            @if($testimonial->designation)
                                <p class="text-muted mb-0 small">{{ $testimonial->designation }}</p>
                            @endif
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted mb-0">Patient testimonials will appear here when published.</p>
        @endif
    </div>
</section>
