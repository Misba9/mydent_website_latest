<section class="md-section md-section--soft md-reveal" aria-labelledby="md-centers-title">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="md-section__eyebrow">Centers</span>
                <h2 id="md-centers-title" class="md-section__title mb-2">Vist mydent center</h2>
                <p class="md-section__lead mb-0">Book a consultation at one of our dental centers.</p>
            </div>
            <a href="{{ route('medicalContact') }}" class="btn btn-outline-primary" data-turbo="false">All centers</a>
        </div>

        <div class="md-center-grid">
            <article class="md-center-card">
                <img src="{{ asset('storage/imgs/Hyderabad.png') }}"
                     alt="MyDent Hyderabad"
                     width="280"
                     height="180"
                     loading="lazy"
                     onerror="{!! imgOnErrorFallback('images/mydent-care.jpeg') !!}">
                <div class="md-center-card__body">
                    <h3>Hyderabad — Jubilee Hills</h3>
                    <p>
                        mydent, H.No 8, No:36 Pillar, 2-293/82/a/787, Road, Jubilee Hills,
                        Hyderabad, Telangana 500033
                    </p>
                    <p class="mb-3">
                        <a href="tel:+{{ getSettingValue('region_code') }}{{ getSettingValue('contact_no') }}">
                            +{{ getSettingValue('region_code') }} {{ getSettingValue('contact_no') }}
                        </a>
                    </p>
                    <x-front.button href="{{ route('medicalAppointment') }}" size="sm">Book Appointment</x-front.button>
                </div>
            </article>

            <article class="md-center-card">
                <img src="{{ asset('storage/imgs/Banglore.png') }}"
                     alt="MyDent Bengaluru"
                     width="280"
                     height="180"
                     loading="lazy"
                     onerror="{!! imgOnErrorFallback('images/mydent-care.jpeg') !!}">
                <div class="md-center-card__body">
                    <h3>Bengaluru — Basaveshwarnagar</h3>
                    <p>
                        Dhanvantari 2B, 80 ft road, KHB colony, 2nd stage Basaveshwarnagar,
                        Bengaluru, Karnataka 560079
                    </p>
                    <p class="mb-3">Clear aligners &amp; dental consultations</p>
                    <x-front.button href="{{ route('medicalAppointment') }}" size="sm">Book Appointment</x-front.button>
                </div>
            </article>
        </div>
    </div>
</section>
