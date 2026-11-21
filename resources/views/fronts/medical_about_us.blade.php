@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical_about_us') }}
@endsection
@section('meta_description', 'Discover the MyDent journey. Our in-house Indian labs design FDA-cleared, highly comfortable aligners to deliver perfect smiles under orthodontic supervision.')

@section('front-content')
    @include('fronts.partials.banner-carousel', [
        'page' => 'why-mydent',
        'fallbackImage' => 'images/invisible.jpeg',
        'fallbackTitle' => 'Why mydent?',
    ])

    <section class="md-section md-section--warm md-reveal">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="md-section__title">Why choose our aligners?</h2>
                <p class="md-section__lead">
                    Experience the future of dental care with premium clear aligners designed for comfort and confidence.
                </p>
            </div>

            @php
                $features = [
                    ['num' => '01', 'title' => 'Super Comfortable', 'desc' => 'Lightweight, flexible, and irritation-free aligners for everyday comfort.', 'img' => 'images/smile-with-aligners.jpg', 'flip' => false],
                    ['num' => '02', 'title' => 'US FDA Cleared', 'desc' => 'Certified high-quality aligners that meet global safety standards.', 'img' => 'images/fda-quality-aligners.jpg', 'flip' => true],
                    ['num' => '03', 'title' => 'Almost Invisible', 'desc' => 'Clear design keeps your smile natural and confident.', 'img' => 'images/invisible.jpeg', 'flip' => false],
                    ['num' => '04', 'title' => 'Fully Personalised', 'desc' => 'Tailored to fit your unique dental structure perfectly.', 'img' => 'images/3d-dental-scan.jpg', 'flip' => true],
                    ['num' => '05', 'title' => 'Pay After Approval', 'desc' => 'Approve your plan before paying. Flexible payment options.', 'img' => 'images/Pay-after-approval.png', 'flip' => false],
                    ['num' => '06', 'title' => 'Safe Materials', 'desc' => 'BPA-free, phthalate-free — safe for long-term dental use.', 'img' => 'images/safe-aligner-material.jpg', 'flip' => true],
                    ['num' => '07', 'title' => 'Advanced Tech', 'desc' => 'Precision 3D printing with FDA and CE-approved materials.', 'img' => 'images/Advance-technology.png', 'flip' => false],
                    ['num' => '08', 'title' => 'mydent Promise', 'desc' => 'Your smile, our mission.', 'img' => 'images/mydent-care.jpeg', 'flip' => true],
                ];
            @endphp

            @foreach($features as $feature)
                <div class="md-feature-row {{ $feature['flip'] ? 'md-feature-row--flip' : '' }} md-reveal">
                    <div class="md-feature-row__copy">
                        <div class="md-feature-row__num">{{ $feature['num'] }}</div>
                        <h3 class="md-feature-row__title">{{ $feature['title'] }}</h3>
                        <p class="md-feature-row__desc">{{ $feature['desc'] }}</p>
                    </div>
                    <div class="md-feature-row__media">
                        <img src="{{ asset($feature['img']) }}" alt="{{ $feature['title'] }}">
                    </div>
                </div>
            @endforeach

            <div class="text-center mt-5">
                <x-front.button href="{{ route('medicalAppointment') }}">Book a Scan</x-front.button>
            </div>
        </div>
    </section>
@endsection
