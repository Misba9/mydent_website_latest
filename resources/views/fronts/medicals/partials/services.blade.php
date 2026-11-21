    <!-- start services section -->
    <section class="services-section overflow-hidden p-b-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-4">
                    <div class="text-xxl-start text-center mb-lg-0 mb-5">
                        <h5 class="text-primary top-heading fs-6 mb-3">{{__('messages.web.services')}}</h5>
                        <h2 class="pb-2">{{__('messages.web.we_cover_a_big___')}}</h2>
                        <p class="paragraph pb-3">
                            {{__('messages.web.we_provide_the_special_tips___')}}
                        </p>
                        <a href="{{ route('medicalServices') }}"
                           class="btn btn-primary">{{__('messages.web.all_services')}}</a>
                    </div>
                </div>
                <div class="col-xxl-8 after-rectangle-shape position-relative right-shape mt-lg-5 mt-xxl-0">
                    <div class="services-carousel z-index-1">
                            @foreach($frontMedicalServices as $frontMedicalService)
                        <div class="services-block">
                            <div class="row position-relative z-index-1">
                                @if(isset($frontMedicalService[0]))
                                <div class="col-md-6 text-center services-innner-block">
                                    <div class="icon-box mx-auto d-flex align-items-center justify-content-center">
                                        <img src="{{ $frontMedicalService[0]['icon'] }}" alt="Emergency" class="img-fluid object-image-cover" />
                                    </div>
                                    <h4 class="text-primary">{{ $frontMedicalService[0]['name'] }}</h4>
                                    <p class="paragraph pb-3">
                                        {{ $frontMedicalService[0]['short_description'] }}
                                    </p>
                                </div>
                                @endif
                                    @if(isset($frontMedicalService[1]))
                                <div class="col-md-6 text-center services-innner-block">
                                    <div class="icon-box mx-auto d-flex align-items-center justify-content-center">
                                        <img src="{{ $frontMedicalService[1]['icon'] }}" alt="Emergency" class="img-fluid object-image-cover" />
                                    </div>
                                    <h4 class="text-primary">{{ $frontMedicalService[1]['name']}}</h4>
                                    <p class="paragraph pb-3">
                                        {{ $frontMedicalService[1]['short_description'] }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end services section -->
