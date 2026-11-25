<section class="md-section md-section--white md-reveal" aria-labelledby="md-why-title">
    <div class="container">
        <div class="md-split">
            <div>
                <span class="md-section__eyebrow">Why MyDent?</span>
                <h2 id="md-why-title" class="md-section__title">Built for confidence, comfort, and clinical care</h2>
                <p class="md-section__lead mb-3">
                    MyDent focuses on clear aligner treatment with modern clinics, patient-first guidance,
                    and a seamless path from enquiry to appointment.
                </p>
                <ul class="md-split__list">
                    <li>Clear aligner expertise with a dedicated clinic experience</li>
                    <li>Centers in Jubilee Hills, Hyderabad and Basaveshwarnagar, Bengaluru</li>
                    <li>Easy online booking and product shop for dental care essentials</li>
                    <li>Transparent communication from first scan to treatment support</li>
                </ul>
                <x-front.button href="{{ route('medicalAppointment') }}">Schedule a Consultation</x-front.button>
            </div>
            <div class="md-split__media md-split__media--natural">
                <img src="{{ asset('images/before-after.png') }}"
                     alt="Confident smile with MyDent care"
                     width="1536"
                     height="1024"
                     loading="lazy"
                     onerror="{!! imgOnErrorFallback('images/smile-with-aligners.jpg') !!}">
            </div>
        </div>
    </div>
</section>
