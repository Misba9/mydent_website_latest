@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.privacy_policy') }}
@endsection
@section('front-content')
    <div class="privacy-policy-page">
        <!-- Hero Section -->
        <section class="hero-content-section bg-white p-t-60 p-b-40">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1 class="mb-3 fw-bold text-gray-900">{{ __('messages.privacy_policy') }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('medical') }}">{{ __('messages.web.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.privacy_policy') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <!-- Privacy Content -->
        <section class="privacy-content-section bg-light py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow-sm border-0 rounded-15 p-4 p-md-5 bg-white">
                            <div class="privacy-body text-gray-800 lh-lg">
                                {!! $privacyPolicy['privacy_policy'] !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
