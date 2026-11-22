@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical') }}
@endsection
@section('meta_description', 'Experience India\'s premium clear aligners with MyDent. Get custom-made invisible braces starting with a free 3D scan at home or at our clinics.')

@section('front-content')
    <div class="home-page">
        @include('fronts.medicals.partials.hero')
        @include('fronts.medicals.partials.benefits')
        @include('fronts.medicals.partials.how-aligners-work')
        @include('fronts.medicals.partials.journey')
        @include('fronts.medicals.partials.why-aligners')
        @include('fronts.medicals.partials.issues')
        @include('fronts.medicals.partials.stories')
        @include('fronts.medicals.partials.shop-categories')
        @include('fronts.medicals.partials.centers')
        @include('fronts.medicals.partials.testimonials')
        @include('fronts.medicals.partials.faqs')
        @include('fronts.medicals.partials.cta')
        @include('fronts.medicals.partials.fab')
    </div>
@endsection

@include('fronts.medicals.partials.scripts')
