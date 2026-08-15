@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical_about_us') }}
@endsection

<!-- Banner Image -->
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

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #20c997, #17a2b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
        }

        .section-header p {
            font-size: 1.2rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 80px;
            background: white;
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(32, 201, 151, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #20c997, #17a2b8);
        }

        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(32, 201, 151, 0.15);
        }

        .feature-content {
            flex: 1;
            padding: 20px;
            position: relative;
        }

        .feature-number {
            display: inline-block;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #20c997, #17a2b8);
            color: white;
            border-radius: 50%;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            line-height: 60px;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(32, 201, 151, 0.3);
        }

        .feature-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .feature-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #5a6c7d;
            max-width: 500px;
        }

        .feature-image-container {
            flex: 1;
            padding: 20px;
            position: relative;
        }

        .feature-image {
            width: 100%;
            max-width: 450px;
            height: 320px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .feature-image:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 45px rgba(32, 201, 151, 0.2);
        }

        .feature-image-container::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 30px;
            bottom: 30px;
            background: linear-gradient(135deg, #20c997, #17a2b8);
            border-radius: 20px;
            z-index: -1;
            opacity: 0.1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .feature-item {
                flex-direction: column;
                text-align: center;
                padding: 30px 20px;
            }

            .feature-content {
                order: 2;
            }

            .feature-image-container {
                order: 1;
                margin-bottom: 20px;
            }

            .feature-title {
                font-size: 1.8rem;
            }

            .feature-description {
                max-width: 100%;
            }

            .section-header h2 {
                font-size: 2.2rem;
            }
        }

        /* Special styling for the last item */
        .feature-item:last-child {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            color: white;
        }

        .feature-item:last-child .feature-title {
            color: white;
        }

        .feature-item:last-child .feature-description {
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-item:last-child .feature-number {
            background: white;
            color: #20c997;
        }

        /* Decorative elements */
        .decorative-circle {
            position: absolute;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #20c997, #17a2b8);
            border-radius: 50%;
            opacity: 0.05;
            z-index: -1;
        }

        .decorative-circle-1 {
            top: -50px;
            right: -50px;
        }

        .decorative-circle-2 {
            bottom: -50px;
            left: -50px;
        }
    </style>

@php
    $banners = \App\Models\MainBanner::where('page', 'why-mydent')->get(); // or dynamic slug
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
       
    </style>
</section>
@endif



<!-- Feature Blocks -->
<div class="container">
        <div class="section-header">
            <h2>Why Choose Our Aligners?</h2>
            <p>Experience the future of dental care with our premium clear aligners designed for your comfort and confidence.</p>
        </div>

        <div class="feature-item">
            <div class="decorative-circle decorative-circle-1"></div>
            <div class="feature-content">
                <div class="feature-number">01</div>
                <h3 class="feature-title">Super Comfortable</h3>
                <p class="feature-description">Lightweight, flexible, and irritation-free aligners for everyday comfort.</p>
            </div>
            <div class="feature-image-container">
                <img src="https://www.hovedentalclinic.co.uk/wp-content/uploads/2022/12/bigstock-Clear-Aligner-Dental-Night-Gua-463488851.jpg" alt="Super Comfortable" class="feature-image">
            </div>
        </div>

        <div class="feature-item">
            <div class="decorative-circle decorative-circle-2"></div>
            <div class="feature-image-container">
                <img src="https://media.licdn.com/dms/image/v2/C5612AQHzceafAjRHeQ/article-cover_image-shrink_720_1280/article-cover_image-shrink_720_1280/0/1520094130601?e=2147483647&v=beta&t=7UK1vPY1uuYzn6VyTFCy5ooHEJPXlKKwYLDMI8JtRcA" alt="US FDA Cleared" class="feature-image">
            </div>
            <div class="feature-content">
                <div class="feature-number">02</div>
                <h3 class="feature-title">US FDA Cleared</h3>
                <p class="feature-description">Certified high-quality aligners that meet global safety standards.</p>
            </div>
        </div>

        <div class="feature-item">
            <div class="decorative-circle decorative-circle-1"></div>
            <div class="feature-content">
                <div class="feature-number">03</div>
                <h3 class="feature-title">Almost Invisible</h3>
                <p class="feature-description">Clear design keeps your smile natural and confident.</p>
            </div>
            <div class="feature-image-container">
                <img src="https://www.lastingimpressionsdentalgroup.com/wp-content/uploads/clear-aligners-2012.jpg" alt="Almost Invisible" class="feature-image">
            </div>
        </div>

        <div class="feature-item">
            <div class="decorative-circle decorative-circle-2"></div>
            <div class="feature-image-container">
                <img src="https://chennaiorthodontics.com/wp-content/uploads/2019/03/invisalign.jpg" alt="Fully Personalised" class="feature-image">
            </div>
            <div class="feature-content">
                <div class="feature-number">04</div>
                <h3 class="feature-title">Fully Personalised</h3>
                <p class="feature-description">Tailored to fit your unique dental structure perfectly.</p>
            </div>
        </div>

        <div class="feature-item">
            <div class="decorative-circle decorative-circle-1"></div>
            <div class="feature-content">
                <div class="feature-number">05</div>
                <h3 class="feature-title">Pay After Approval</h3>
                <p class="feature-description">Approve your plan before paying. Flexible payment options.</p>
            </div>
            <div class="feature-image-container">
                <img src="https://www.cppr.in/wp-content/uploads/2017/12/Th11-Paper-money.jpg" alt="Pay After Approval" class="feature-image">
            </div>
        </div>

        <div class="feature-item">
            <div class="decorative-circle decorative-circle-2"></div>
            <div class="feature-image-container">
                <img src="https://whistlecontent.s3.ap-south-1.amazonaws.com/s3gallery/1742796364_Aligner_page_banner_mobile.jpg" alt="Safe Materials" class="feature-image">
            </div>
            <div class="feature-content">
                <div class="feature-number">06</div>
                <h3 class="feature-title">Safe Materials</h3>
                <p class="feature-description">BPA-free, Phthalate-free — safe for long-term dental use.</p>
            </div>
        </div>

        <div class="feature-item">
            <div class="decorative-circle decorative-circle-1"></div>
            <div class="feature-content">
                <div class="feature-number">07</div>
                <h3 class="feature-title">Advanced Tech</h3>
                <p class="feature-description">Precision 3D printing with FDA and CE-approved materials.</p>
            </div>
            <div class="feature-image-container">
                <img src="https://www.dentevim.com/upload/ortodontik-tedavi-secenekleri-dis-telleri-mi-seffaf-plaklar-mi-1.jpg" alt="Advanced Tech" class="feature-image">
            </div>
        </div>

        <div class="feature-item">
            <div class="decorative-circle decorative-circle-2"></div>
            <div class="feature-image-container">
                <img src="https://childhoodsmiles.com/wp-content/uploads/2022/05/All-about-clear-aligners-and-invisible-braces.jpg" alt="mydent magic" class="feature-image">
            </div>
            <div class="feature-content">
                <div class="feature-number">08</div>
                <h3 class="feature-title">Mydent Promise</h3>
                <p class="feature-description">your smile our mission</p>
            </div>
        </div>
    </div>

