    <!-- start FAQ section -->
    <section class="faq-section p-t-100 p-b-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="faq-image text-center">
                        <img src="{{ asset('storage/imgs/faqs.jpeg') }}" alt="FAQ" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-content">
                        <h5 class="text-primary top-heading fs-6 mb-3">{{__('messages.web.faq')}}</h5>
                        <h2 class="pb-4">{{__('messages.web.frequently_asked_questions')}}</h2>
                        
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <!--{{__('messages.web.how_do_i_schedule_an_appointment')}}?-->
                                        What is mydent and how does it work?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <!--{{__('messages.web.you_can_schedule_an_appointment_by_calling_our_office_or_using_our_online_booking_system')}}-->
                                        mydent is a leading clear aligner brand offering invisible, removable aligners to straighten your teeth comfortably. We provide free scans, expert treatment planning, and doorstep delivery of aligners, all monitored by certified orthodontists.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <!--{{__('messages.web.what_insurance_plans_do_you_accept')}}?-->
                                        How do I start my treatment with mydent?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <!--{{__('messages.web.we_accept_most_major_insurance_plans_please_contact_our_office_to_verify_your_specific_coverage')}}-->
                                        Your journey begins with a 3D dental scan at a nearby clinic or from the comfort of your home. Our orthodontic team evaluates your case, creates a personalized plan, and once approved, we deliver your aligners to your doorstep.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <!--{{__('messages.web.what_should_i_bring_to_my_first_appointment')}}?-->
                                        Are mydent aligners suitable for all types of dental problems?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <!--{{__('messages.web.please_bring_your_insurance_card_id_medical_history_and_any_current_medications_you_are_taking')}}-->
                                        Yes! mydent specializes in both mild and complex cases. With hybrid mechanics (mini-implants or braces support when needed), we ensure almost every smile can be treated effectively
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <!--{{__('messages.web.do_you_offer_telehealth_services')}}?-->
                                        What is the cost of clear aligners?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <!--{{__('messages.web.yes_we_offer_telehealth_services_for_certain_types_of_appointments_please_call_our_office_to_learn_more')}}-->
                                        Aligner costs depend on the complexity of your dental issue and the length of treatment. Pricing typically varies for mild, moderate, and severe cases. For accurate pricing, a scan and consultation are recommended.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end FAQ section -->
