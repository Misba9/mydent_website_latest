<footer class="md-footer">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-4 col-md-6">
                <img src="{{ asset('assets/image/mydent-logo-transparent.png') }}"
                     alt="MyDent Clear Aligners"
                     class="md-footer__logo"
                     width="180"
                     height="60"
                     onerror="{!! imgOnErrorFallback('assets/image/mydent-logo-transparent.png') !!}" />
                <p class="md-footer__brand-text mb-0">
                    MyDent Clear Aligners — comfortable, discreet orthodontic care with expert supervision across our clinics.
                </p>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5>Explore</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ url('/') }}">Home</a></li>
                    <li class="mb-2"><a href="{{ route('medicalDoctors') }}">Aligners</a></li>
                    <li class="mb-2"><a href="{{ route('medicalServices') }}">Smileverse</a></li>
                    <li class="mb-2"><a href="{{ route('products.index') }}" data-turbo="false">Products</a></li>
                    <li class="mb-2"><a href="{{ route('medicalAboutUs') }}">Why MyDent?</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5>Support</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ route('medicalAppointment') }}">Book Appointment</a></li>
                    <li class="mb-2"><a href="{{ route('medicalContact') }}" data-turbo="false">Dental Centers</a></li>
                    <li class="mb-2"><a href="{{ route('front.faqs') }}">FAQs</a></li>
                    <li class="mb-2"><a href="{{ route('terms.conditions') }}">Terms &amp; Conditions</a></li>
                    <li class="mb-2"><a href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5>Contact</h5>
                <div class="footer-info">
                    <div class="d-flex align-items-center footer-info__block mb-3">
                        <div class="footer-info__footer-icon fs-6 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-phone" aria-hidden="true"></i>
                        </div>
                        <a href="tel:+{{ getSettingValue('region_code') }}{{ getSettingValue('contact_no') }}">
                            +{{ getSettingValue('region_code') }} {{ getSettingValue('contact_no') }}
                        </a>
                    </div>
                    <div class="d-flex align-items-center footer-info__block mb-3">
                        <div class="footer-info__footer-icon fs-6 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                        </div>
                        <a href="mailto:{{ getSettingValue('email') }}">{{ getSettingValue('email') }}</a>
                    </div>
                    <div class="d-flex align-items-start footer-info__block mb-3">
                        <div class="footer-info__footer-icon fs-6 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                        </div>
                        <span class="fw-light">
                            Jubilee Hills, Hyderabad &amp; Basaveshwarnagar, Bengaluru
                        </span>
                    </div>
                </div>
                <div class="footer-subcribe mt-3">
                    {{ Form::open(['id' => 'subscribeForm', 'class' => 'subscribe-form subscription-form']) }}
                    <div class="subscribeForm-message"></div>
                    <div class="input-group">
                        {{ Form::email('email', null, ['class' => 'form-control form-control-transparent', 'id' => 'email', 'placeholder' => __('messages.web.enter_your_email'), 'required', 'aria-label' => 'Email for newsletter']) }}
                        <button type="submit" class="input-group-text" id="basic-addon2" aria-label="Subscribe">
                            <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                        </button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div class="border-top-primary mt-5 pt-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <p class="mb-0 text-white-50">
                    {{ __('messages.web.all_rights_reserved') }} © {{ date('Y') }} {{ getAppName() }}
                </p>
                <div class="footer-social-links d-flex gap-3">
                    <a href="https://youtube.com/@mydent-k4k?si=ejiXPlD-u6ixftNe" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                        <i class="fab fa-youtube" aria-hidden="true"></i>
                    </a>
                    <a href="https://x.com/mydent_my?t=dH5GC-UDV-zn4S0EWgA-_A&s=08" target="_blank" rel="noopener noreferrer" aria-label="X">
                        <i class="fab fa-twitter" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.instagram.com/mydent.in" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
