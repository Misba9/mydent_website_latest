    <!-- start doctor profiles section -->
   <!-- start doctor profiles section -->
<section class="doctors-section p-t-100 p-b-100">
    <div class="container">
        <div class="text-center mb-5">
            <h5 class="text-primary top-heading fs-6 mb-3">{{__('messages.web.our_doctors')}}</h5>
            <h2 class="pb-2">{{__('messages.web.meet_our_specialists')}}</h2>
            <p class="paragraph mx-auto" style="max-width: 700px;">
                {{__('messages.web.our_team_of_medical_specialists_is_here_to_provide_you_with_the_best_care_available')}}
            </p>
        </div>
        <div class="row">
            @foreach($doctorsList as $doctor)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="doctor-card text-center bg-white shadow-sm rounded-3 h-100">
                        <div class="doctor-img mb-3">
                            <img src="{{ $doctor->user->profile_image ?? asset('assets/front/images/doctor-placeholder.png') }}"
                                 alt="{{ $doctor->user->full_name ?? 'Doctor' }}"
                                 class="img-fluid rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <h4 class="doctor-name">{{ $doctor->user->full_name }}</h4>
                        <p class="doctor-speciality text-primary">{{ __('messages.web.specialist') }}</p>
                    </div>
                </div>

            @endforeach

        </div>
        
    </div>
</section>
<!-- end doctor profiles section -->
