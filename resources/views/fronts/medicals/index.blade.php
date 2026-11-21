@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical') }}
@endsection
@section('meta_description', 'Experience India\'s premium clear aligners with MyDent. Get custom-made invisible braces starting with a free 3D scan at home or at our clinics.')

@section('front-content')
    <div class="home-page">
        @include('fronts.medicals.partials.hero')
        @include('fronts.medicals.partials.issues')
        @include('fronts.medicals.partials.how-aligners-work')
        @include('fronts.medicals.partials.why-aligners')
        @include('fronts.medicals.partials.supervision-app')
        @include('fronts.medicals.partials.characteristics')
        @include('fronts.medicals.partials.journey')
        @include('fronts.medicals.partials.pricing')
        @include('fronts.medicals.partials.stories')
        @include('fronts.medicals.partials.shop-categories')
        @include('fronts.medicals.partials.blogs')
        @include('fronts.medicals.partials.appointment')
        @include('fronts.medicals.partials.services')
        @include('fronts.medicals.partials.stats')
        @include('fronts.medicals.partials.doctors')
        @include('fronts.medicals.partials.faqs')
        @include('fronts.medicals.partials.cta')
        @include('fronts.medicals.partials.results')
        @include('fronts.medicals.partials.fab')
    </div>
@endsection

@include('fronts.medicals.partials.scripts')
