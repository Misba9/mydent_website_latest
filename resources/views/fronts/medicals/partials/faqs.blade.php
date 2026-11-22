<section class="md-section md-section--soft md-reveal md-faq" aria-labelledby="md-faq-title">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-5">
                <span class="md-section__eyebrow">FAQ</span>
                <h2 id="md-faq-title" class="md-section__title">Questions, answered</h2>
                <p class="md-section__lead">
                    Common questions about MyDent clear aligners, consultations, and appointments.
                </p>
                <img src="{{ asset('storage/imgs/faqs.jpeg') }}"
                     alt="MyDent FAQ"
                     class="img-fluid rounded"
                     style="border-radius: var(--md-radius-lg);"
                     width="560"
                     height="420"
                     loading="lazy">
            </div>
            <div class="col-lg-7">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                What is MyDent and how does it work?
                            </button>
                        </h3>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                MyDent is a clear aligner brand offering invisible, removable aligners. We provide scans, expert treatment planning, and aligner delivery with monitoring by certified orthodontists.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How do I start treatment with MyDent?
                            </button>
                        </h3>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Begin with a dental assessment at a nearby clinic or as guided by our team. After evaluation, you receive a personalized plan and begin treatment once approved.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                How do I book an appointment?
                            </button>
                        </h3>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Use the Book Appointment page on this website, or contact a MyDent center in Hyderabad or Bengaluru for assistance.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                What about products and dental care essentials?
                            </button>
                        </h3>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Browse the Products shop for MyDent care essentials, add items to your cart, and checkout through the existing secure payment flow.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('front.faqs') }}" class="btn btn-outline-primary">More FAQs</a>
                </div>
            </div>
        </div>
    </div>
</section>
