@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical_services') }}
@endsection

@section('front-content')


   <!-- Banner Section -->

@php
    $banners = \App\Models\MainBanner::where('page', 'smileverse')->get(); // or dynamic slug
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





    <div class="services-page">
        <!-- start hero section -->
        <!-- <section class="hero-content-section bg-white p-t-100 p-b-100">
            <div class="container p-t-30">
                <div class="col-12">
                    <div class="hero-content text-center">
                        <h1 class="mb-3">
                            {{ __('messages.web.services') }}
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('medical') }}"> {{ __('messages.web.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.web.services') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section> -->

<!-- How Do Braces Work Section -->
<section class="braces-work-section p-t-100 p-b-100 bg-light">
    <div class="container">
        <h2 class="text-danger text-center mb-4">How do braces work?</h2>
        <p class="text-center fs-5 mx-auto" style="max-width: 800px;">
            Dental
braces apply gentle, consistent pressure to gradually move teeth
into
their proper position, while also reshaping the supporting
bone. This treatment
effectively corrects various alignment issues, including gaps, crooked teeth,
and
bite problems
.

        </p>
    </div>
</section>


<!-- Why Choose Toothsi Section -->
<section class="why-choose-section p-t-100 p-b-100 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <!-- Text Content -->
            <div class="col-md-6">
                <h2 class="mb-4">What makes mydent invisible aligners a preferred option over braces?</h2>
                <p class="mb-3">Don’t let metal

braces
hold you back— shine confidently with Mydent</strong></p>
                <h5 class="text-danger">#NoWiresJustSmiles</h5>
            </div>
            <!-- Video Content -->
             <div class="col-lg-6 text-center">
    <div class="ratio ratio-16x9">
        @php
            $section4Video = \App\Models\HomepageVideo::where('section', 'section-4')->first();
        @endphp

        @if($section4Video)
            <video class="w-100 h-100" style=" object-fit: cover;border-radius: 20px;
                                                box-shadow: 0 8px 20px rgba(26, 24, 27, 0.4);" autoplay loop muted playsinline>
                <source src="{{ $section4Video->video_path }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <p class="text-muted">Video coming soon...</p>
        @endif
    </div>
</div>
        </div>
    </div>
</section>

<!-- Types of Braces Section -->
<section class="types-of-braces-section p-t-100 p-b-100 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Types of Braces</h2>
        <div class="row">
            @php
                $braces = [
                    ['img' => 'https://zaradental.com/wp-content/uploads/2021/02/ZA-Blog-053.jpg', 'title' => 'Metal Braces', 'desc' => 'Traditional and highly effective braces.'],
                    ['img' => 'https://happysmile.in/wp-content/uploads/2021/04/ceramic-braces-e1619251565587.jpg', 'title' => 'Ceramic Braces', 'desc' => 'Tooth-colored braces for a less visible appearance.'],
                    ['img' => 'https://gloorthodontics.ca/wp-content/uploads/2023/02/metal-braces-glo-orthodontics-west-bedford.jpg', 'title' => 'Self-Ligating Braces', 'desc' => 'Uses clips instead of elastic bands.'],
                    ['img' => 'https://www.didsburydentalpractice.dentist/wp-content/uploads/2022/11/shutterstock_1305039190.jpg', 'title' => 'Lingual Braces', 'desc' => 'Placed behind the teeth, completely hidden.'],
                    ['img' => 'https://www.indiadens.com/blog/wp-content/uploads/2022/01/clear-aligners-invisible-braces-cost-delhi.jpg', 'title' => 'Clear Aligners', 'desc' => 'Removable and invisible option for teeth alignment.'],
                    ['img' => 'https://3.imimg.com/data3/HR/RH/MY-9086783/2-500x500.jpg', 'title' => 'Mini Braces', 'desc' => 'Smaller than traditional braces with a sleek look.'],
                ];
            @endphp

            @foreach($braces as $brace)
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center p-3 shadow-sm">
                    <img src="{{ asset($brace['img']) }}" class="img-fluid mb-3" alt="{{ $brace['title'] }}">
                    <h5 class="mb-2">{{ $brace['title'] }}</h5>
                    <p>{{ $brace['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Fact Check Section -->
<section style="padding: 50px 0; background-color: #f9f9f9;">
    <div class="container">
        <h2 style="text-align: center; color: #dc3545; font-size: 32px; font-weight: bold; margin-bottom: 40px;">
            The Braces Struggle – Painful bites, hidden smiles, snack restrictions
        </h2>
        <div class="row">
            <!-- Box 1 -->
            <div class="col-md-6">
                <div style="background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05); margin-bottom: 20px; border-left: 8px solid #4eb1ba;">
                    <div style="display: flex; align-items: center;">
                        <h4 style="margin: 0; font-size: 20px; color: #333;">Pain & Discomfort</h4>
                    </div>
                    <p style="margin-top: 15px; font-size: 16px; color: #555;">
                       Braces can cause soreness especially after tightening. It's temporary, but definitely noticeable
                    </p>
                </div>
            </div>
            <!-- Box 2 -->
            <div class="col-md-6">
                <div style="background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05); margin-bottom: 20px; border-left: 8px solid #ff9f43;">
                    <div style="display: flex; align-items: center;">
                        <h4 style="margin: 0; font-size: 20px; color: #333;">Staining & Yellowing</h4>
                    </div>
                    <p style="margin-top: 15px; font-size: 16px; color: #555;">
                        Foods with strong pigments like turmeric, tomato paste, or colored candies can stain braces and teeth.
                    </p>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
<div class="row">
    <!-- Box 3 -->
    <div class="col-md-6">
        <div style="background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05); margin-bottom: 20px; border-left: 8px solid #ffc107;">
            <div style="display: flex; align-items: center;">
                <h4 style="margin: 0; font-size: 20px; color: #333;">Oral Hygiene Challenges</h4>
            </div>
            <p style="margin-top: 15px; font-size: 16px; color: #555;">
                Brushing and flossing around brackets takes extra effort, increasing the risk of cavities and gum issues.
            </p>
        </div>
    </div>
    <!-- Box 4 -->
    <div class="col-md-6">
        <div style="background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05); margin-bottom: 20px; border-left: 8px solid #e74c3c;">
            <div style="display: flex; align-items: center;">
                <h4 style="margin: 0; font-size: 20px; color: #333;">Food Restrictions</h4>
            </div>
            <p style="margin-top: 15px; font-size: 16px; color: #555;">
                Crunchy, sticky, or chewy foods (like caramel, chips, gum, or soda) can damage braces and are best avoided
            </p>
        </div>
    </div>
</div>

<!-- Row 3 -->
<div class="row">
    <!-- Box 5 -->
    <div class="col-md-6">
        <div style="background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05); margin-bottom: 20px; border-left: 8px solid #28a745;">
            <div style="display: flex; align-items: center;">
                <h4 style="margin: 0; font-size: 20px; color: #333;">Frequent Clinic Visits</h4>
            </div>
            <p style="margin-top: 15px; font-size: 16px; color: #555;">
               Braces need regular tightening and monitoring, meaning more appointments and time in the chair.
            </p>
        </div>
    </div>
    <!-- Box 6 -->
    <div class="col-md-6">
        <div style="background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05); margin-bottom: 20px; border-left: 8px solid #6f42c1;">
            <div style="display: flex; align-items: center;">
                <h4 style="margin: 0; font-size: 20px; color: #333;">Speech Difficulty</h4>
            </div>
            <p style="margin-top: 15px; font-size: 16px; color: #555;">
                Braces can tempporarily affect speech, causing minor lisps or discomfort while talking during the adjustment phase.
            </p>
        </div>
    </div>
</div>

    </div>
</section>


<section class="braces-work-section p-t-100 p-b-100" style="background-color:#D8F1F8">
    <div class="container">
        <h2 class="text-danger text-center mb-4">Braces cost in India</h2>
        <p class="text-center fs-7 mx-auto mb-4" style="max-width: 800px;">
        Braces
Cost in India
Wondering how much
braces really cost in India?
The price can vary based on the complexity of your
case, treatment time, and the type of
braces you choose. Traditional metal
braces usually start around ₹25,000,
while more advanced options like lingual
braces can go up to ₹2,00,000.
But there's a better way.
mydent Aligners offer an affordable with EMI options
.Our clear, comfortable, and modern alternative, aligners are nearly invisible, removable, and custom
starting at just ₹3,019/month
- made for your smile.
        </p>
    </div>
</section>


<section class="braces-work-section p-t-100 p-b-100" style="background-color:#fffff">
    <div class="container">
        <h2 class="text-danger text-center mb-4">say hello to invisible aligners!</h2>
        <p class="text-center fs-7 mx-auto mb-4" style="max-width: 800px;">
        Here is mydent Aligners!
Looking for a discreet way to straighten your teeth? Invisible aligners
 also known as clear or transparent braces are the modern alternative to bulky metal braces. At mydent, our aligners are custom designed to gently shift your teeth into perfect alignment using controlled, gradual pressure.
Crafted from high quality, BPA free plastic and 3D-printed for precision, mydent Aligners are not only US FDA approved aligner sheets but also
completely personalized to fit your smile. They’re sleek, subtle, and practically invisible so no one will notice you're wearing 
them. Unlike traditional braces, our aligners are removable, comfortable, and won’t interfere with your favorite meals.
No wires. No restrictions. Just the confidence to smile freely, every single day.
Your smile journey stays private and stylsih with mydent.
Would you like a shorter version for social media or a tagline to go with it?

        </p>
    </div>
</section>


<!-- Braces vs Aligners Section with Vertical Divider -->
<div style="background-color: #f9f9f9; padding: 50px 0;">
    <div class="container">
        <!-- Header with icons and line -->
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 40px;">
            <!-- <img src="{{ asset('fronts/images/braces-icon.png') }}" alt="Braces" style="height: 60px; margin-right: 20px;"> -->

            <div style="flex-grow: 1; height: 2px; background-color: #ddd; position: relative;">
                <span style="position: absolute; left: 50%; top: -15px; transform: translateX(-50%); background: #f9f9f9; padding: 0 15px; font-size: 22px; font-weight: bold; color: #ff2e2e;">
                    Braces vs Aligners
                </span>
            </div>

            <!-- <img src="{{ asset('fronts/images/aligner-icon.png') }}" alt="Aligners" style="height: 60px; margin-left: 20px;"> -->
        </div>

        <!-- Comparison with vertical divider -->
        <div style="display: flex; justify-content: space-between; gap: 0; position: relative;">

            <!-- Braces Column -->
            <div style="width: 45%;">
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #e74c3c;">
                    <p style="margin: 0; font-weight: bold;">Painful</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #e74c3c;">
                    <p style="margin: 0; font-weight: bold;">Visible</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #e74c3c;">
                    <p style="margin: 0; font-weight: bold;">Not Removable</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #e74c3c;">
                    <p style="margin: 0; font-weight: bold;">Food Restrictions</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #e74c3c;">
                    <p style="margin: 0; font-weight: bold;">Tough to Maintain Oral Hygiene</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #e74c3c;">
                    <p style="margin: 0; font-weight: bold;">Regular Clinic Visits</p>
                </div>
                <div style="margin-bottom: 0px; padding: 20px; background: white; border-left: 5px solid #e74c3c;">
                    <p style="margin: 0; font-weight: bold;">Results Can Not Be Visualised</p>
                </div>
            </div>

            <!-- Vertical Divider -->
            <div style="width: 1px; background-color: #ccc; margin: 0 20px;"></div>

            <!-- Aligners Column -->
            <div style="width: 45%;">
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #2ecc71;">
                    <p style="margin: 0; font-weight: bold;">Painless</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #2ecc71;">
                    <p style="margin: 0; font-weight: bold;">Invisible</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #2ecc71;">
                    <p style="margin: 0; font-weight: bold;">Removable</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #2ecc71;">
                    <p style="margin: 0; font-weight: bold;">No Food Restrictions</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #2ecc71;">
                    <p style="margin: 0; font-weight: bold;">Easy to Maintain Oral Hygiene</p>
                </div>
                <div style="margin-bottom: 20px; padding: 20px; background: white; border-left: 5px solid #2ecc71;">
                    <p style="margin: 0; font-weight: bold;">Minimal Clinic Visits</p>
                </div>
                <div style="margin-bottom: 0px; padding: 20px; background: white; border-left: 5px solid #2ecc71;">
                    <p style="margin: 0; font-weight: bold;">Results Can Be Visualised with the Virtual Smile Plan</p>
                </div>
            </div>            
        </div>        
    </div>  
</div>

<!-- Appearance, Food, Pain, and Hygiene Section -->
<div style="background-color: #D8F1F8; padding: 60px 20px;">
    <div class="container" style="max-width: 1200px; margin: auto;">

        <!-- Appearance -->
        <div style="margin-bottom: 40px;">
            <h2 style="color: #333; font-size: 28px; font-weight: bold; margin-bottom: 15px;">Appearance</h2>
            <p style="font-size: 16px; color: #444; line-height: 1.6;">
                Look Good While You Straighten Your Smile: Let’s be honest metal braces are hard to miss
. The shiny brackets on each tooth can make people, especially kids and teens,
feel self-conscious. Some even avoid smiling in photos or speaking up in public. For adults, especially those in client-facing or speaking
roles, metal braces can affect confidence at work too. But there’s a better way.
mydent Invisible Aligners are the clear, comfortable solution to straighten your teeth without drawing any attention. They're
almost invisble, so most people won’t even notice you're wearing them. Whether you're in school or the board room, you can smile, speak, and live confidently without the awkwardness of metal braces.Choose mydent and let your smile shine through beautifully, naturally, and without compromise. Let me know if you'd like this formatted for a brochure, website section, or an Instagram post.

            </p>
        </div>

        <!-- Food and drink restrictions -->
        <div style="margin-bottom: 40px;">
            <h2 style="color: #333; font-size: 28px; font-weight: bold; margin-bottom: 15px;">Food and drink restrictions</h2>
            <p style="font-size: 16px; color: #444; line-height: 1.6;">
                Enjoy the Freedom to Eat What You Love: One of the biggest downsides of traditional metal braces. The long list of food restrictions. Crunchy snacks, sticky treats, and even some fruits are off limits because they can damage the wires or get stuck in the brackets. Plus, sugary drinks can increase the risk of cavities around those hard-to-clean areas. But with mydent Aligners, there’s no need to give up your favorite foods. Our
clear aligners are completely removable, so you can eat and drink whatever you like just take them out during meals and pop them
back in after a quick rinse or brush. No food rules.No hassle. Just a smoother, simpler path to a healthier smile.
            </p>
        </div>

        <!-- Painful -->
        <div style="margin-bottom: 40px;">
            <h2 style="color: #333; font-size: 28px; font-weight: bold; margin-bottom: 15px;">Painful</h2>
            <p style="font-size: 16px; color: #444; line-height: 1.6;">
                Bye-Bye Pain, Hello Comfort: Let’s face it metal braces can hurt. The constant tightening, the poking wires, and those sharp brackets rubbing against the inside of your cheeks often leave your mouth sore and irritated. Many people find it hard to cope with the discomfort and, unfortunately, some even give up halfway through their treatment.
mydent Aligners are designed with comfort in mind. No wires. No brackets. No painful tightening. Just smooth,custom fit trays that gently guide your teeth into places. And Since you remove them while eating, there’s no added pressure or food getting stuck.It’s a treatment that fits your lifestyle and keeps you smiling from day one.

            </p>
        </div>

        <!-- Hygiene -->
        <div>
            <h2 style="color: #333; font-size: 28px; font-weight: bold; margin-bottom: 15px;">Hygiene</h2>
            <p style="font-size: 16px; color: #444; line-height: 1.6;">
               Stay Fresh, Stay Confident. Bad breath? Total deal breaker and we totally get it. It can instantly kill the vibe and leave you feeling uncomfortable. If you’re wearing metal braces, you already know how tricky it is to keep your teeth clean. Food gets trapped in hard-to-reach spots, especially around brackets and wires, making thorough cleaning a daily challenge.This buildup can lead to poor oral hygiene, which not only affects your dental health especially in social settings where every detail but can also impact your smile counts.Confidence But here’s where *mydent* steps in. Our clear, removable aligners make oral hygiene simple and stress-free. No brackets,no wires just smooth, comfortable aligners that you can take out to brush and floss properly. That means fresher breath, healthier teeth, and a lot more confidence. Say good bye to awkward moments and hello to a cleaner, brighter smile with *mydent*
            </p>
        </div>

    </div>
</div>

        <!-- end our-team section -->
        <section class="services-section bg-secondary p-t-100">
            <div class="container">
                <div class="row justify-content-center">
                    @foreach($services as $service)
                    <div class="col-xl-4 col-md-6 services-block d-flex align-items-stretch">
                        <div class="card mx-lg-2 flex-fill">
                            <div class="card-body text-center d-flex flex-column">
                                <div class="services-icon-box mx-auto d-flex align-items-center justify-content-center">
                                    <img src="{{ $service->icon }}" alt="Emergency" class="img-fluid object-image-cover">
                                </div>
                                <h4 class="text-primary"> {{ $service->name }}</h4>
                                <p class="paragraph pb-3">
                                    {{ $service->short_description }}
                                </p>
                                <a href="{{ route('serviceBookAppointment',$service->id) }}"
                                   class="btn btn-primary mt-auto align-self-center">
                                    <span>{{ __('messages.web.book_an_appointment') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- end our-team section -->

        <!-- start services counter section -->
        <!--<section class="services-counter-section p-t-100 p-b-100">-->
        <!--    <div class="container">-->
        <!--        <div class="bg-white rounded-20 box-shadow py-3 py-sm-0">-->
        <!--            <div class="row">-->
        <!--                <div class="col-xl-3 col-6 services-counter-block">-->
        <!--                    <div class="text-center my-4 my-sm-5 pipe">-->
        <!--                        <h4 class="text-primary fs-1 fw-bolder mb-3">{{ $data['specializationsCount'] }}</h4>-->
        <!--                        <h5 class="mb-0">{{ __('messages.specializations') }}</h5>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="col-xl-3 col-6 services-counter-block">-->
        <!--                    <div class="text-center my-4 my-sm-5 pipe">-->
        <!--                        <h4 class="text-primary fs-1 fw-bolder mb-3">{{ $data['servicesCount'] }}</h4>-->
        <!--                        <h5 class="mb-0">{{ __('messages.web.services') }}</h5>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="col-xl-3 col-6 services-counter-block">-->
        <!--                    <div class="text-center my-4 my-sm-5 pipe">-->
        <!--                        <h4 class="text-primary fs-1 fw-bolder mb-3">{{ $data['doctorsCount'] }}</h4>-->
        <!--                        <h5 class="mb-0">{{ __('messages.doctors') }}</h5>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="col-xl-3 col-6 services-counter-block">-->
        <!--                    <div class="text-center my-4 my-sm-5 pipe">-->
        <!--                        <h4 class="text-primary fs-1 fw-bolder mb-3">{{ $data['patientsCount'] }}</h4>-->
        <!--                        <h5 class="mb-0">{{ __('messages.web.satisfied_patient') }}</h5>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</section>-->
        
        
        
        <section class="services-counter-section p-t-100 p-b-100">
            <div class="container">
                <div class="bg-white rounded-20 box-shadow py-3 py-sm-0">
                    <div class="row">
                        <div class="col-xl-3 col-6 services-counter-block">
                            <div class="text-center my-4 my-sm-5 pipe">
                                <h4 class="text-primary fs-1 fw-bolder mb-3">100 +</h4>
                                <h5 class="mb-0">Clinics</h5>
                            </div>
                        </div>
                        <div class="col-xl-3 col-6 services-counter-block">
                            <div class="text-center my-4 my-sm-5 pipe">
                                <h4 class="text-primary fs-1 fw-bolder mb-3">25 +</h4>
                                <h5 class="mb-0">Cities</h5>
                            </div>
                        </div>
                        <div class="col-xl-3 col-6 services-counter-block">
                            <div class="text-center my-4 my-sm-5 pipe">
                                <h4 class="text-primary fs-1 fw-bolder mb-3">200 +</h4>
                                <h5 class="mb-0">{{ __('messages.doctors') }}</h5>
                            </div>
                        </div>
                        <div class="col-xl-3 col-6 services-counter-block">
                            <div class="text-center my-4 my-sm-5 pipe">
                                <h4 class="text-primary fs-1 fw-bolder mb-3">100k +</h4>
                                <h5 class="mb-0">{{ __('messages.web.satisfied_patient') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end services counter section -->
    </div>


    <!-- FAQ Section -->
<div style="background-color: #fff; padding: 60px 20px;">
    <div class="container" style="max-width: 1000px; margin: auto;">
        <h2 style="text-align: center; font-size: 32px; color: #333; font-weight: bold; margin-bottom: 40px;">FAQs</h2>

        <!-- FAQ Item 1 -->
        <div style="margin-bottom: 25px; border-bottom: 1px solid #ccc; padding-bottom: 15px;">
            <h4 style="color: #E40046; font-size: 20px; margin-bottom: 8px;">1. What is mydent and how does it work?</h4>
            <p style="color: #444; font-size: 16px;">mydent is a leading clear aligner brand offering invisible, removable aligners to straighten your teeth comfortably. We provide free scans, expert treatment planning, and doorstep delivery of aligners, all monitored by certified orthodontists.</p>
        </div>

        <!-- FAQ Item 2 -->
        <div style="margin-bottom: 25px; border-bottom: 1px solid #ccc; padding-bottom: 15px;">
            <h4 style="color: #E40046; font-size: 20px; margin-bottom: 8px;">2. How do I start my treatment with mydent?</h4>
            <p style="color: #444; font-size: 16px;">Your journey begins with a 3D dental scan at a nearby clinic or from the comfort of your home. Our orthodontic team evaluates your case, creates a personalized plan, and once approved, we deliver your aligners to your doorstep.</p>
        </div>

        <!-- FAQ Item 3 -->
        <div style="margin-bottom: 25px; border-bottom: 1px solid #ccc; padding-bottom: 15px;">
            <h4 style="color: #E40046; font-size: 20px; margin-bottom: 8px;">3. Are mydent aligners suitable for all types of dental problems?</h4>
            <p style="color: #444; font-size: 16px;">Yes! mydent specializes in both mild and complex cases. With hybrid mechanics (mini-implants or braces support when needed), we ensure almost
every smile can be treated effectively</p>
        </div>

        <!-- FAQ Item 4 -->
        <div style="margin-bottom: 25px; border-bottom: 1px solid #ccc; padding-bottom: 15px;">
            <h4 style="color: #E40046; font-size: 20px; margin-bottom: 8px;">4. What is the cost of clear aligners?</h4>
            <p style="color: #444; font-size: 16px;">Aligner costs depend on the complexity of your dental issue and the length of treatment. Pricing typically varies for mild, moderate, and severe cases.
                        For accurate pricing, a scan and consultation are recommended.</p>
        </div>

        <!-- FAQ Item 5 -->
        <div style="margin-bottom: 25px;border-bottom: 1px solid #ccc; padding-bottom: 15px;">
            <h4 style="color: #E40046; font-size: 20px; margin-bottom: 8px;">5. How long does the treatment take with mydent aligners?</h4>
            <p style="color: #444; font-size: 16px;">Treatment duration varies based on case severity. Mild cases may take 6–8 months, while complex cases can take 12–18 months. You'll receive a customized plan outlining your exact timeline.</p>
        </div>
        
        <div style="margin-bottom: 25px;">
            <h4 style="color: #E40046; font-size: 20px; margin-bottom: 8px;">6. How can I book an appointment for a scan or consultation?</h4>
            <p style="color: #444; font-size: 16px;">Booking an appointment with mydent is quick and easy! You can schedule a free scan through our website, mobile app, or by contacting our support team via WhatsApp. Choose a clinic visit or opt for a home scan at your convenience..</p>
        </div>
    </div>
</div>

@endsection
