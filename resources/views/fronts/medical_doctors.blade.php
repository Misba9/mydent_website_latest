@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical_doctors') }}
@endsection

@section('front-content')

<style>
    .alligners{
        overflow-x: hidden;
    }
</style>
<div class="alligners">
   <!-- Banner Image -->
@php
    $banners = \App\Models\MainBanner::where('page', 'alligners')->get(); // or dynamic slug
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





    @php
        $styleCss = 'style';
    @endphp
    <!-- <div class="our-team-page"> -->
        <!-- start hero section -->
        <!-- <section class="hero-content-section bg-white p-t-100 p-b-100">
            <div class="container p-t-30">
                <div class="col-12">
                    <div class="hero-content text-center">
                        <h1 class="mb-3">
                            {{ __('messages.web.our_team') }}
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('medical') }}">{{ __('messages.web.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.web.our_team') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- end hero section -->

        <!-- end our-team section -->
        <!-- <section class="our-team-section bg-secondary">
            <div class="container">
                <div class="row justify-content-center">
                    @foreach($doctors as $doctor)
                    <div class="col-xl-4 col-md-6 our-team-block d-flex align-items-stretch">
                        <div class="card mx-lg-2 flex-fill">
                            <div class="card-body text-center d-flex flex-column">
                                <div class="card-image mb-4 rounded-circle">
                                    <img src="{{ $doctor->user->profile_image }}" alt="Infy Care" class="img-fluid rounded-circle object-image-cover" />
                                </div>
                                <h4 class="text-primary">{{ $doctor->user->full_name }}</h4>
                                <label class="designation-label pb-4 mb-3 d-block">
                                    {{ $doctor->specializations->first()->name }}
                                </label>
                                <ul class="social-media d-flex justify-content-center" >
                                    @if(!empty($doctor->twitter_url))
                                        <li class="pe-2">
                                            <a target="_blank" href="{{ $doctor->twitter_url }}"><i
                                                        class="fab fa-twitter"></i></a>
                                        </li>
                                    @endif
                                    @if(!empty($doctor->linkedin_url))
                                        <li class="pe-2">
                                            <a target="_blank" href="{{ $doctor->linkedin_url }}"><i
                                                        class="fab fa-linkedin"></i></a>
                                        </li>
                                    @endif
                                    @if(!empty($doctor->instagram_url))
                                        <li class="pe-2">
                                            <a target="_blank" href="{{ $doctor->instagram_url }}"><i
                                                        class="fab fa-instagram"></i></a>
                                        </li>
                                    @endif
                                </ul>
                                <a href="{{ route('doctorBookAppointment',$doctor->id) }}"
                                   class="doctor-appointment-btn btn btn-primary mt-auto align-self-center">
                                    <span>{{ __('messages.web.book_an_appointment') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section> -->
        <!-- end our-team section -->
    <!-- </div> -->


<section style="padding: 60px 20px; background-color: #ffffff; font-family: Arial, sans-serif;">
   <div style="max-width: 1200px; margin: auto; display: flex; flex-wrap: wrap; align-items: center; gap: 40px;">

                <h2 style="font-size: 28px; font-weight: 700; color: red; text-align: center;">
                    Smile more, worry less with mydent clear aligners.
                </h2>
                <!-- Left Side Image -->
               <div style="flex: 1 1 45%; text-align: center;">
    <video autoplay muted loop playsinline
        style="width: 100%;
               max-height: 450px;
               object-fit: contain;
               display: block;
               border-radius: 20px;
               box-shadow: 0 8px 20px rgba(26, 24, 27, 0.4);">
        <source src="{{ asset('storage/vids/heroandchat.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>


            
                <!-- Right Side Content -->
                <div style="flex: 1 1 45%;">
                  
                  <p style="font-size: 16px; color: #4B5563; margin-bottom: 20px; line-height: 1.6;">
                    Teeth aligners are clear plastic trays that slowly shift your teeth into the desired position. They’re a popular, discreet alternative to traditional braces and offer a convenient way to unravel your smile without the hassle of wires or brackets.
                  </p>
                  <p style="font-size: 16px; color: #4B5563; margin-bottom: 30px;">
                    Want to understand how it works for you?
                  </p>
            
                  <!-- WhatsApp Button -->
                  <a href="https://wa.me/919381590963" target="_blank" style="display: inline-block; background-color: red; color: white; padding: 12px 24px; font-size: 16px; font-weight: bold; border-radius: 8px; text-decoration: none;">
                    Chat With Us
                  </a>
                </div>
   </div>
</section>




<!-- start clear aligners section -->
<section class="p-t-100 p-b-100" style="background-color: #d1efef;">
    <div class="container">
        <h4 class="text-center mb-4">What are clear aligners and how do they work?</h6>
        <div class="row align-items-center">
            <!-- Left side bullet points -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="d-flex flex-column gap-4">
                    <div class="text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/13730/13730911.png" alt="Icon 1" class="mb-2" style="height: 60px;" />
                        <h5 class="fw-bold">Removable and Convenient</h5>
                        <p class="text-muted">You can remove them while eating or brushing.</p>
                    </div>
                    <div class="text-center">
                        <img src="https://static.vecteezy.com/system/resources/previews/026/183/901/non_2x/hide-icon-eyes-hidden-symbol-invisible-icon-dead-invisible-sign-no-view-blind-icon-can-t-see-hidden-visibility-vector.jpg" alt="Icon 2" class="mb-2" style="height: 60px;" />
                        <h5 class="fw-bold">Virtually Invisible</h5>
                        <p class="text-muted">No metal brackets or wires, just a confident smile.</p>
                    </div>
                    <div class="text-center">
                        <img src="https://media.istockphoto.com/id/1162803307/vector/configuration-customize-icon.jpg?s=612x612&w=0&k=20&c=zw3JYxa43A1juJ58aq58cM5IRLZJhD1swSZAkkQO2mU=" alt="Icon 3" class="mb-2" style="height: 60px;" />
                        <h5 class="fw-bold">Custom-Made for You</h5>
                        <p class="text-muted">Each aligner is crafted based on your dental needs.</p>
                    </div>
                </div>
            </div>

            <!-- Right side YouTube video -->
           <!-- Right side YouTube video -->
<div class="col-lg-6 text-center">
    <div class="ratio ratio-16x9">
        @php
            $section3Video = \App\Models\HomepageVideo::where('section', 'section-3')->first();
        @endphp

        @if($section3Video)
            <video class="w-100 h-100" style="object-fit: cover;border-radius: 20px;
               box-shadow: 0 8px 20px rgba(26, 24, 27, 0.4);" autoplay loop muted playsinline>
                <source src="{{ $section3Video->video_path }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <p class="text-muted">Video coming soon...</p>
        @endif
    </div>
</div>

</section>

<!-- start dental issues section -->
<section class="p-t-50 p-b-100" style="background-color: #D8F1F8;">
    <div class="container position-relative">
                    <h2 class="text-center mb-5" style="padding-top: 30px;">Teeth alignment issues we can fix</h2>
            
                    {{-- Left Arrow --}}
                    {{-- Left Arrow --}}
        <button id="scrollLeft"
            class="btn position-absolute top-50 start-0 translate-middle-y"
            style="z-index: 10; 
               border-radius: 50%; 
               width: 50px; 
               height: 50px; 
               min-width: 50px;
               min-height: 50px;
               background: linear-gradient(135deg, #ffffff, #f8f9fa); 
               border: 2px solid #e9ecef; 
               color: #495057; 
               font-size: 20px; 
               font-weight: bold;
               padding: 0;
               line-height: 1;
               transition: all 0.3s ease; 
               box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
               cursor: pointer;"
            onmouseover="this.style.transform='translateY(-50%) scale(1.1)'; 
                     this.style.background='linear-gradient(135deg, #667eea, #764ba2)'; 
                     this.style.color='white'; 
                     this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.4)'; 
                     this.style.borderColor='#667eea';"
            onmouseout="this.style.transform='translateY(-50%) scale(1)'; 
                    this.style.background='linear-gradient(135deg, #ffffff, #f8f9fa)'; 
                    this.style.color='#495057'; 
                    this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'; 
                    this.style.borderColor='#e9ecef';"
            onmousedown="this.style.transform='translateY(-50%) scale(0.95)';"
            onmouseup="this.style.transform='translateY(-50%) scale(1.1)';">
            <i class="tio-chevron-left" style="display: inline-block; vertical-align: middle;">←</i>
        </button>

            {{-- Right Arrow --}}
        <button id="scrollRight"
                class="btn position-absolute top-50 end-0 translate-middle-y"
                style="z-index: 10; 
                   border-radius: 50%; 
                   width: 50px; 
                   height: 50px; 
                   min-width: 50px;
                   min-height: 50px;
                   background: linear-gradient(135deg, #ffffff, #f8f9fa); 
                   border: 2px solid #e9ecef; 
                   color: #495057; 
                   font-size: 20px; 
                   font-weight: bold;
                   padding: 0;
                   line-height: 1;
                   transition: all 0.3s ease; 
                   box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
                   cursor: pointer;"
                onmouseover="this.style.transform='translateY(-50%) scale(1.1)'; 
                     this.style.background='linear-gradient(135deg, #667eea, #764ba2)'; 
                     this.style.color='white'; 
                     this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.4)'; 
                     this.style.borderColor='#667eea';"
                onmouseout="this.style.transform='translateY(-50%) scale(1)'; 
                    this.style.background='linear-gradient(135deg, #ffffff, #f8f9fa)'; 
                    this.style.color='#495057'; 
                    this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'; 
                    this.style.borderColor='#e9ecef';"
                onmousedown="this.style.transform='translateY(-50%) scale(0.95)';"
                onmouseup="this.style.transform='translateY(-50%) scale(1.1)';">
                <i class="tio-chevron-right" style="display: inline-block; vertical-align: middle;">→</i>
        </button>

        {{-- Scrollable Card Row --}}
        <div id="scrollCards" class="d-flex gap-4 px-5"
             style="overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none;">
            <style>
                #scrollCards::-webkit-scrollbar {
                    display: none;
                }
            </style>

            @php
                $cards = [
                    ['slug' => 'teethgaps', 'video' => 'p1c1vi1.mp4', 'title' => 'Overbite', 'desc' => 'When the upper front teeth significantly overlap the lower front teeth while biting.'],
                    ['slug' => 'crookedteeth', 'video' => 'p1c1vi2.mp4', 'title' => 'Underbite', 'desc' => 'Occurs when the lower teeth extend beyond the upper teeth, affecting appearance and bite.'],
                    ['slug' => 'overbite', 'video' => 'p1c1vi4.mp4', 'title' => 'Crossbite', 'desc' => 'Upper and lower teeth do not align correctly, often causing wear or jaw problems.'],
                    ['slug' => 'open-bite', 'video' => 'p1c1vi5.mp4', 'title' => 'Open Bite', 'desc' => 'Front upper and lower teeth do not meet when the mouth is fully closed.'],
                    ['slug' => 'crossbite', 'video' => 'p1c1vi6.mp4', 'title' => 'Open Bite', 'desc' => 'Front upper and lower teeth do not meet when the mouth is fully closed.'],
                    ['slug' => 'underbite', 'video' => 'p1c1vi7.mp4', 'title' => 'Open Bite', 'desc' => 'Front upper and lower teeth do not meet when the mouth is fully closed.'],
                ];
            @endphp

            @foreach ($cards as $card)
                <a href="{{ route('issue.show', $card['slug']) }}" style="text-decoration: none; color: inherit;">
                    <div style="min-width: 300px; max-width: 300px;border-radius: 20px;">
                        <video class="w-100" style="height: 350px; object-fit: cover; border-radius: 20px;
               " autoplay loop muted playsinline>
                            <source src="{{ asset('storage/vids/' . $card['video']) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </a>
            @endforeach

            {{-- Static Images --}}
            <div style="min-width: 300px; max-width: 300px;border-radius: 20px;" >
                <img src="{{ asset('storage/vids/i1.jpeg') }}" class="w-100" style="height: 350px; object-fit: cover;border-radius: 20px;" alt="i1">
            </div>

            <div style="min-width: 300px; max-width: 300px;border-radius: 20px;" >
                <img src="{{ asset('storage/vids/i2.jpeg') }}" class="w-100" style="height: 350px; object-fit: cover;border-radius: 20px;" alt="i2">
            </div>
        </div>
    </div>
</section>

<!-- Why should you go for clear aligners section -->

<section class="p-t-100 p-b-100" style="background-color: #fff;">
    <div class="container">
        <h2 class="text-center mb-5">Why should you go for clear aligners?</h2>

        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle" style="border-radius: 15px; overflow: hidden;">
                <thead class="bg-white">
                    <tr>
                        <th class="fw-bold fs-5 text-start p-3">The facts</th>
                        <th style="background-color: #bfeaf6;">
                            <div class="text-danger fw-bold fs-5">Clear aligners</div>
                        </th>
                        <th class="fw-bold fs-5">Braces</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data = [
                            ['text' => 'Conveniently removable during meals and brushing', 'clear' => true, 'braces' => false],
                            ['text' => 'Virtually invisible for a discreet look', 'clear' => true, 'braces' => false],
                            ['text' => 'No dietary limitations — enjoy your favorite foods', 'clear' => true, 'braces' => false],
                            ['text' => 'Simple to maintain oral hygiene — brush and floss with ease!', 'clear' => true, 'braces' => false],
                            ['text' => 'Requires frequent dental appointments', 'clear' => false, 'braces' => true],
                            ['text' => "Offers a more comfortable experience", 'clear' => true, 'braces' => false],
                            ['text' => 'Delivers quicker results (typically 6–8 months)', 'clear' => true, 'braces' => false],
                        ];
                    @endphp

                    @foreach($data as $row)
                        <tr style="background-color: {{ $loop->even ? '#f5fafe' : '#ffffff' }};">
                            <td class="text-start p-3">{{ $row['text'] }}</td>
                            <td style="background-color: #e2f8ff;">
                                @if($row['clear'])
                                    <span class="text-success fs-4">&#10003;</span> <!-- Check -->
                                @else
                                    <span class="text-danger fs-4">&#10007;</span> <!-- Cross -->
                                @endif
                            </td>
                            <td>
                                @if($row['braces'])
                                    <span class="text-success fs-4">&#10003;</span>
                                @else
                                    <span class="text-danger fs-4">&#10007;</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section style="background: #D8F1F8; padding: 40px 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
  <div class="aligner-container" style="display: flex; gap: 40px; flex-wrap: wrap;">

    <!-- Left Side -->
    <div class="aligner-text" style="flex: 1; min-width: 300px; margin-left: 20px;">
      <h2 style="font-size: 36px; margin-bottom: 20px; color: #333;">Clear aligners at Transparent Prices</h2>
      <p style="font-size: 15px; color: #555; line-height: 1.6;">
        At mydent, we combine quality, comfort, and affordability. Our clear aligners are carefully designed using the latest 3D printing technology, crafted in our own in-house labs right here in India. Because we control the entire process, we're able to offer world-class aligners at prices that won’t break the bank.
      </p>

      <h2 style="font-size: 36px; margin-top: 40px; margin-bottom: 20px; color: #333;">What Affects the Cost?</h2>
      <p style="font-size: 15px; color: #555; line-height: 1.6;">
        The cost of your mydent aligners depends on your treatment needs. Simpler corrections require fewer aligners and shorter treatment time, while more complex cases may need additional aligners and closer monitoring by our orthodontic experts.
        <br><br>
        Whether you're looking for a more discreet alternative to braces or a treatment plan that fits your lifestyle, mydent Aligners offer a perfect balance of precision, convenience, and value.
      </p>
    </div>

    <!-- Right Side -->
    <div class="aligner-right" style="flex: 1; min-width: 300px; display: flex; flex-direction: column; align-items: center;">
      <img src="https://www.future-doctor.de/wp-content/uploads/2024/08/shutterstock_2480850611.jpg" alt="mydent Aligners" style="width: 100%; max-width: 400px; height: auto; border-radius: 10px; margin-bottom: 30px; object-fit: cover;">

      <div class="aligner-cards" style="display: flex; width: 100%; max-width: 400px; flex-wrap: wrap;">
        <!-- Card 1 -->
        <div class="card" style="flex: 1; background-color: #f0f0f0; padding: 20px; border-radius: 8px 0 0 8px; text-align: center; transform: translateY(20px); min-width: 120px;">
          <h3 style="margin: 0; font-size: 18px; color: #333;">Traditional braces</h3>
          <p style="margin: 5px 0 0; font-size: 16px; color: #666;">₹35,000 to ₹90,000</p>
        </div>

        <!-- Card 2 -->
        <div class="card" style="flex: 1; background-color: #f0f0f0; padding: 20px; text-align: center; transform: translateY(-10px); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); min-width: 120px;">
          <h3 style="margin: 0; font-size: 18px; color: #333;">mydent aligners</h3>
          <p style="margin: 5px 0 0; font-size: 16px; color: #666;">₹52,999 to ₹1,30,000</p>
        </div>

        <!-- Card 3 -->
        <div class="card" style="flex: 1; background-color: #f0f0f0; padding: 20px; border-radius: 0 8px 8px 0; text-align: center; transform: translateY(20px); min-width: 120px;">
          <h3 style="margin: 0; font-size: 18px; color: #333;">Other aligners</h3>
          <p style="margin: 5px 0 0; font-size: 16px; color: #666;">₹1,50,000 to ₹4,00,000</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Responsive styles -->
  <style>
    @media (max-width: 768px) {
      .aligner-container {
        flex-direction: column;
        gap: 30px;
      }

      .aligner-cards {
        flex-direction: column;
      }

      .aligner-cards .card {
        border-radius: 8px !important;
        transform: none !important;
        margin-bottom: 15px;
      }
    }
  </style>
</section>



<section style="padding: 60px 20px; background-color: #ffffff; font-family: Arial, sans-serif;">
  <div style="max-width: 1200px; margin: 0 auto;">
    <h2 style="font-size: 32px; font-weight: 700; color: #111827; text-align: center; margin-bottom: 10px;">
      Why We're the Right Choice
    </h2>
    <p style="font-size: 18px; color: #6B7280; text-align: center; margin-bottom: 40px;">
      Fully Personalised Custom-made aligners tailored precisely to your unique dental structure for perfect results.
    </p>

    <div class="why-cards-wrapper">
      <!-- Card 1 -->
      <div class="why-card">
        <svg xmlns="http://www.w3.org/2000/svg" class="why-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2l4 -4M12 2l7 4v5c0 5.523 -3.582 10.74 -7 12c-3.418 -1.26 -7 -6.477 -7 -12V6l7 -4z" />
        </svg>
        <h3 class="why-title">Super Comfortable</h3>
        <p class="why-text">Lightweight, flexible, and irritation-free aligners designed for all-day comfort.</p>
      </div>

      <!-- Card 2 -->
      <div class="why-card">
        <svg xmlns="http://www.w3.org/2000/svg" class="why-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        <h3 class="why-title">Almost Invisible</h3>
        <p class="why-text">Clear design that lets you smile with confidence. Aligners so discreet, no one will notice.</p>
      </div>

      <!-- Card 3 -->
      <div class="why-card">
        <svg xmlns="http://www.w3.org/2000/svg" class="why-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M18.364 5.636a9 9 0 11-12.728 0m12.728 0A9 9 0 005.636 18.364m12.728-12.728L12 12m0 0v3m0-3H9" />
        </svg>
        <h3 class="why-title">Safe Materials</h3>
        <p class="why-text">Made from BPA-free, Phthalate. Free materials—completely safe for long-term use.</p>
      </div>

      <!-- Card 4 -->
      <div class="why-card">
        <svg xmlns="http://www.w3.org/2000/svg" class="why-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 7l-8-4-8 4m16 0v8l-8 4m8-4l-8 4m-8-4v-8m0 8l8 4m-8-4l8-4m0-8v8" />
        </svg>
        <h3 class="why-title">Advanced Technology</h3>
        <p class="why-text">Precision 3D printing using FDA and CE-approved materials ensures accuracy and reliability.</p>
      </div>
    </div>
  </div>

  <style>
    .why-cards-wrapper {
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .why-card {
      background: #F9FAFB;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      padding: 30px;
      width: 100%;
      text-align: center;
      transition: transform 0.3s;
    }

    .why-card:hover {
      transform: translateY(-5px);
    }

    .why-icon {
      width: 50px;
      height: 50px;
      color: #1F2937;
      margin-bottom: 20px;
    }

    .why-title {
      font-size: 20px;
      font-weight: 600;
      color: #111827;
      margin-bottom: 10px;
    }

    .why-text {
      font-size: 16px;
      color: #6B7280;
    }

    @media (min-width: 768px) {
      .why-cards-wrapper {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
      }

      .why-card {
        width: calc(25% - 30px);
        max-width: 270px;
      }
    }
  </style>
</section>





<section class="p-t-50 p-b-100 mt-25" style="background-color: #F9FAFB;">
    <div class="container" >
        <h2 class="mb-5 text-center" style="margin-top:50px">Our Testimonials</h2>
        <div class="row justify-content-center">
            @php
                $section1Videos = \App\Models\HomepageVideo::where('section', 'section-5')->orderBy('order')->get();
            @endphp

            @foreach($section1Videos as $index => $video)
                <div class="col-md-4 mb-4">
                    <div class="card border rounded p-3 shadow-sm text-center" style="height: 320px;">
                        <video class="w-100" style="height: 180px; object-fit: cover;" autoplay loop muted playsinline>
                            <source src="{{ $video->video_path }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="mt-3">
                            <h5 class="fw-bold mt-2">{{ $video->title }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
      
      
      
      

<section style="padding: 60px 20px; background-color: #f8f8f8; font-family: Arial, sans-serif;">
  <h2 style="text-align: center; color: red; font-size: 28px; font-weight: bold; margin-bottom: 30px;">Why Us</h2>

  <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 20px; padding: 10px;">
    <!-- Superstar Card 1 -->
    <div style="min-width: 220px; background-color: #fff; padding: 20px; border-radius: 12px; 
                box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
      <img src="{{ asset('storage/imgs/1.png') }}" 
           alt="Superstar 1" 
           style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
    </div>

    <!-- Superstar Card 2 -->
    <div style="min-width: 220px; background-color: #fff; padding: 20px; border-radius: 12px; 
                box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
      <img src="{{ asset('storage/imgs/9.png') }}" 
           alt="Superstar 2" 
           style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
    </div>

    <!-- Superstar Card 3 -->
    <div style="min-width: 220px; background-color: #fff; padding: 20px; border-radius: 12px; 
                box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
      <img src="{{ asset('storage/imgs/11.png') }}" 
           alt="Superstar 3" 
           style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
    </div>
    
    
   <div style="min-width: 220px; background-color: #fff; padding: 20px; border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
    <video autoplay muted loop playsinline 
           style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;">
        <source src="{{ asset('storage/vids/4whyus.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

  <div style="min-width: 220px; background-color: #fff; padding: 20px; border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
    <video autoplay muted loop playsinline 
           style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px;margin-top:25px">
        <source src="{{ asset('storage/vids/5whyus.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>


  </div>
</section>



</div>


@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollContainer = document.getElementById('scrollCards');

        document.getElementById('scrollLeft').addEventListener('click', function () {
            scrollContainer.scrollBy({ left: -320, behavior: 'smooth' });
        });

        document.getElementById('scrollRight').addEventListener('click', function () {
            scrollContainer.scrollBy({ left: 320, behavior: 'smooth' });
        });
    });
</script>
@endpush
