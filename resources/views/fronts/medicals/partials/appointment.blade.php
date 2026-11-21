    <!-- start appointment section -->
    <section class="appointmnet-section p-t-100 p-b-100">
        <div class="container">
            <div class="bg-primary border-bmr-100 appointmnet-section__inner-block">
                <div class="row align-items-center">
                    <div class="col-lg-6 align-self-end d-none d-lg-block">
                        <img src="{{ asset('assets/front/images/appointment.png') }}" alt="Book An Appointment" class="img-fluid object-image-cover" />
                    </div>
                    <div class="col-lg-6 contact-form">
                        <h6 class="pb-2 text-white text-center mb-4 pb-3">{{__('messages.web.book_an_appointment')}}</h6>
                        <form action="{{ route('front.home.appointment.book')}}" method="POST" turbo:load>
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="contact-form__input-block">
                                        <input name="first_name" type="text" class="form-control required form-control-transparent"
                                               placeholder="{{ __('messages.doctor.first_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-form__input-block">
                                        <input type="text" name="last_name" class="form-control required form-control-transparent"
                                               placeholder="{{ __('messages.doctor.last_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="contact-form__input-block">
                                        <input type="email" name="email" class="form-control required form-control-transparent"
                                               placeholder="{{ __('messages.web.email') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-form__input-block">
                                        {{ Form::select('doctor_id',$doctors, null,['class' => 'form-select form-control-transparent required', 'data-control'=>"select2", 'required','placeholder' => __('messages.common.select_doctor'), 'id' => 'frontDoctorId']) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-form__input-block position-relative">
                                        <input type="text" name="date" id="frontAppointmentDate"
                                               class="form-control form-control-transparent appointment-calendar" placeholder="{{ __('messages.doctor.select_date') }}"
                                               autocomplete="true" required readonly>
                                        <span
                                                class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4">
                                                <i class="fa-solid fa-calendar-days text-white post"></i>
                                            </span>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center mt-4">
                                    <p style="color:#fff">Book your 3D Scan or impression, one of our doctor will connect with you via video call to discuss and guide you through your personalized treatment plan.</p>
                                    <button type="submit" class="btn btn-light">{{__('messages.web.appointment_now')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end appointment section -->
