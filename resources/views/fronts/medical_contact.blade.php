@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical_contact') }}
@endsection
@section('front-content')
    @php
        $styleCss = 'style';
    @endphp
    <div class="contact-page">
        <!-- start hero section -->
        <!-- <section class="hero-content-section bg-white p-t-100 p-b-100">
            <div class="container p-t-30">
                <div class="col-12">
                    <div class="hero-content text-center">
                        <h1 class="mb-3">
                            {{ __('messages.web.contact_us') }}
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('medical') }}">{{ __('messages.web.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">  Center</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section> -->



  <!-- Banner Section -->
@php
    $banners = \App\Models\MainBanner::where('page', 'centre')->get(); // or dynamic slug
@endphp


@if($banners->count())
<section class="main-banner mb-4" style="margin-top: 90px;">
    <div id="mainBannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($banners as $key => $banner)
                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="d-block w-100 banner-image">
                    
                    @if($banner->title)
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="banner-title">{{ $banner->title }}</h5>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#mainBannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#mainBannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <!-- Styles -->
    <style>
        .banner-image {
            width: 100%;
            height: 600px;
            object-fit: cover;
        }

        .banner-title {
            font-size: 2rem;
            background-color: rgba(0, 0, 0, 0.5);
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .banner-image {
                height: 400px;
            }

            .banner-title {
                font-size: 1.25rem;
                padding: 8px 16px;
            }
        }

        @media (max-width: 480px) {
            .banner-image {
                height: 250px;
            }

            .carousel-caption {
                bottom: 10px;
            }

            .banner-title {
                font-size: 1rem;
                padding: 6px 12px;
            }
        }
    </style>
</section>
@endif



      
      
      
      
<section style="padding: 40px 20px; background-color: #ffffff; font-family: Arial, sans-serif;text-align: center;">
  <h2 style="color: #e63946; font-size: 35px; margin-bottom: 10px; ">
    Find a mydent Experience Centre Near You
  </h2>
  <p style="font-size: 20px; color: #555;">
Walk into our expert-led clinics and take the first step toward your perfect smile.<br> With
advanced technology and personalized care from skilled orthodontists.  </p>
</section>



<!-- start city list -->

<!-- end city list -->

<!-- start stores section -->
<section class="store-list-section p-t-100 p-b-50">
    <div class="container">
        <div id="store-container" class="row g-4">
            @php
                $stores = [
                    'Delhi' => [
                        ['name' => 'Banglore', 'image' => 'Banglore.png', 'location' => 'Banglore', 'timing' => '10am - 9pm', 'phone' => '+91 9381590963,', 'gmap' => '#'],
                                            ['name' => 'The Lucknowgrapher Studio', 'image' => 'Hyderabad.png', 'location' => 'Hyderabad', 'timing' => '9am - 9pm', 'phone' => '9123456789', 'gmap' => '#'],

                        ['name' => 'Chennai', 'image' => 'chenni.png', 'location' => 'Chennai', 'timing' => '9am - 9pm', 'phone' => '9123456780', 'gmap' => '#'],
                    ],
                    'Mumbai' => [
                        ['name' => 'Smile Zone Mumbai', 'image' => 'Delhi.png', 'location' => 'Delhi', 'timing' => '10am - 9pm', 'phone' => '9988776655', 'gmap' => '#'],
                    ],
                    'Lucknow' => [

                        ['name' => 'The Lucknowgrapher Studio', 'image' => 'kochin.png', 'location' => 'Kochin', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],

                        ['name' => 'The Lucknowgrapher Studio', 'image' => 'Kolkata.png', 'location' => 'Kolkata', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],

                        ['name' => 'The Lucknowgrapher Studio', 'image' => 'mumbai.png', 'location' => 'Mumbai', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],

                        ['name' => 'The Lucknowgrapher Studio', 'image' => 'Nellore.png', 'location' => 'Nellore', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],

                                                ['name' => 'The Lucknowgrapher Studio', 'image' => 'pondicherry.png', 'location' => 'Pondicherry', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],
                              ['name' => 'The Lucknowgrapher Studio', 'image' => 'pune.png', 'location' => 'Pune', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],

                                                ['name' => 'The Lucknowgrapher Studio', 'image' => 'Vijayawada.png', 'location' => 'Vijayawada', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],
                                                ['name' => 'The Lucknowgrapher Studio', 'image' => 'Vishakapatnam.png', 'location' => 'Vishakapatnam', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],


                        ['name' => 'The Lucknowgrapher Studio', 'image' => 'Goa.png', 'location' => 'Goa', 'timing' => '11am - 6pm', 'phone' => '9123456789', 'gmap' => '#'],
                    ],
                ];
            @endphp

           <section class="store-list-section p-t-100 p-b-50" style="padding-top: 100px; padding-bottom: 50px;">
  <div class="container" style="max-width: 1200px; margin: 0 auto;">
    <div id="store-container" class="row g-4 justify-content-center">
        @foreach($stores as $city => $cityStores)
            @foreach($cityStores as $store)
                  <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center mb-4">
                    <div class="card store-card h-100 shadow-sm" style="width: 300px; border:1px solid #ccc; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column;">
                      <img src="{{ asset('storage/imgs/' . $store['image']) }}"
                           alt="{{ $store['name'] }}"
                           style="width: 100%; height: 200px; object-fit: cover;">
                              <div class="card-body" style="flex: 1; overflow-y: auto; padding: 10px;">
                                <!--<p class="card-text mb-2"><strong>City:</strong> {{ $city }}</p>-->
                                <p class="card-text mb-2"><strong>Location:</strong> {{ $store['location'] }}</p>
                                <p class="card-text mb-2"><strong>Timing:</strong> {{ $store['timing'] }}</p>
                              </div>
                            <div class="card-footer d-flex gap-2" style="padding: 8px; background-color: #f8f9fa; border-top: 1px solid #ddd;">
  <a href="tel:+91{{ $store['phone'] }}" class="btn btn-sm btn-outline-success d-flex align-items-center justify-content-center" style="padding: 0.375rem 0.75rem;">
    <i class="fa fa-phone"></i>
  </a>
  <a href="https://wa.me/919381590963" target="_blank" 
     class="btn btn-sm btn-outline-success d-flex align-items-center justify-content-center" 
     style="font-size: 14px; padding: 0.375rem 0.75rem;">
     <i class="fa fa-calendar-alt me-2"></i>
 Book a consultation
  </a>
</div>

                    </div>
                  </div>
            @endforeach
        @endforeach
    </div>
  </div>

  <style>
    /* Make the card full width on mobile */
    @media (max-width: 576px) {
      .store-card {
        width: 100% !important;
      }
    }

    /* Fixed height for images */
    .store-card img {
      height: 200px;
    }

    /* Limit card body height and enable scrolling if content overflows */
    .store-card .card-body {
      max-height: 120px;
    }
  </style>
</section>

        </div>
    </div>
</section>
<!-- end stores section -->
      

   <section class="services-section py-5">
    <div class="container">
        <h2 class="text-center mb-4">Services Available at Our Centers</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6 text-center">
                <div class="service-image mb-4">
<img src="{{ asset('storage/imgs/c1.jpeg') }}" alt="Service 1" class="img-fluid" style="max-height: 500px; border-radius: 20px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);">
                </div>
            </div>
            <div class="col-md-6 col-lg-6 text-center">
                <div class="service-image mb-4">
                    <img src="{{ asset('storage/imgs/c2.jpeg') }}" alt="Service 2" class="img-fluid" style="max-height: 500px;border-radius: 20px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);">
                </div>
            </div>
        </div>
    </div>
</section>



 <!-- start contact form section -->
 <section class="contact-section bg-secondary p-t-100 p-b-100">
            <div class="container">
                <div class="bg-white rounded-20 box-shadow main-box">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 d-none d-md-block">
                            <div class="card bg-primary contect-information">
                                <div class="card-body">
                                    <h4 class="text-white mb-4 pb-2"> {{__('messages.web.contact_us_for_any_information')}}</h4>
                                    <div class="text-white">
                                        <h5 class="mb-3"> {{__('messages.web.location')}}</h5>
                                        <p class="paragraph text-white">
                                             <!-- {{ getSettingValue('address_one') }} -->
                                            <!--Delhi, India-->
                                           Dhanvantari 2B 80 ft road KHB colony, 2, stage Basaveshwarnagar, Bengaluru, Karnataka 560079

                                            </p>
                                    </div>
                                    <div class="text-white">
                                        <h5 class="mb-3">{{__('messages.web.email')}} &  {{__('messages.web.phone')}}</h5>
                                        <a href=" mailto:{{getSettingValue('email')}}" class="text-decoration-none text-white d-block">
                                            {{ getSettingValue('email') }}
                                        </a>
                                        <a href="  tel:+{{ getSettingValue('region_code') }} {{ getSettingValue('contact_no') }}"
                                           class="text-decoration-none text-white d-block">
                                            +{{ getSettingValue('region_code') }} {{ getSettingValue('contact_no') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 ps-md-0">
                            <form id="enquiryForm" action="{{ route('enquiries.store') }}"
                                  class="contact-form ajax-form" method="POST">
                                @method('post')
                                @csrf
                                <div class="ajax-message"></div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="contact-form__input-block">
                                            {{ Form::text('name',old('name'), ['class' => 'form-control','id'=>'name', 'placeholder' => __('messages.web.name'),'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="contact-form__input-block">
                                            {{ Form::email('email', old('email'), ['class' => 'form-control', 'id' => 'email','placeholder' => __('messages.web.email'),'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="contact-form__input-block">
                                            {{ Form::tel('phone', null,['class' => 'form-control','placeholder' => __('messages.web.phone'),'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")']) }}
                                            {{ Form::hidden('region_code',null,['id'=>'prefix_code']) }}
                                            <span id="valid-msg" class="hide">✓ &nbsp; {{ __('messages.valid_number') }}</span>
                                            <span id="error-msg" class="hide"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="contact-form__input-block">
                                            {{ Form::text('subject', null, ['class' => 'form-control', 'id' => 'subject','placeholder' => __('messages.web.subject'),'required','maxlength'=>'121']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="contact-form__input-block">
                                            {{ Form::textarea('message', null, ['class' => 'form-control form-textarea', 'id' => 'message','placeholder' =>  __('messages.web.message'),'required']) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <div class="g-recaptcha"
                                                 data-sitekey="{{ config('app.google_recaptcha_site_key') }}"
                                                 data-callback="verifyRecaptchaCallback"
                                                 data-expired-callback="expiredRecaptchaCallback"></div>
                                            <input class="form-control d-none" {{$styleCss}}="display:none;" name="
                                        gre_captcha" data-recaptcha="true" data-error="Please complete the Captcha">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-end mt-3">
                                        {{ Form::button(__('messages.web.send_message'),['type'=>'submit','class' => 'btn btn-primary','id'=>'submitBtn']) }}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end contact form section -->
        <!-- start contact information section -->
        <section class="information-section p-t-100 p-b-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-md-6 d-flex align-items-stretch">
                        <div class="card mx-lg-2 flex-fill">
                            <div class="card-body d-flex flex-column">
                                <div class="contact-icon-box d-flex align-items-center justify-content-center mb-4">
                                    <i class="fa-solid fa-phone text-primary fs-3"></i>
                                </div>
                                <h4 class="mb-3 pt-2">  {{__('messages.user.contact_number')}}</h4>
                                <a href=" tel:+{{ getSettingValue('region_code') }} {{ getSettingValue('contact_no') }}" class="text-decoration-none text-gray-100 d-block fw-light">
                                    +{{ getSettingValue('region_code') }} {{ getSettingValue('contact_no') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 d-flex align-items-stretch mt-md-0 mt-4">
                        <div class="card mx-lg-2 flex-fill">
                            <div class="card-body d-flex flex-column">
                                <div class="contact-icon-box d-flex align-items-center justify-content-center mb-4">
                                    <i class="fa-solid fa-envelope text-primary fs-3"></i>
                                </div>
                                <h4 class="mb-3 pt-2">   {{__('messages.web.email_address')}}</h4>
                                <a href=" mailto:{{getSettingValue('email')}}" class="text-decoration-none text-gray-100 d-block fw-light">
                                    {{ getSettingValue('email') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 d-flex align-items-stretch mt-xl-0 mt-4 pt-xl-0 pt-lg-3">
                        <div class="card mx-lg-2 flex-fill">
                            <div class="card-body d-flex flex-column">
                                <div class="contact-icon-box d-flex align-items-center justify-content-center mb-4">
                                    <i class="fa-solid fa-location-dot text-primary fs-3"></i>
                                </div>
                                <h4 class="mb-3 pt-2">{{__('messages.setting.address')}}</h4>
                                <p class="paragraph mb-0">
                                    <!-- {{ getSettingValue('address_one') }} -->
                                      Delhi , India
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end contact information section -->
    </div>
@endsection
