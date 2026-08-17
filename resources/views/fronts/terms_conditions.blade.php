@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.terms_conditions') }}
@endsection
@section('front-content')
    <div class="terms-conditions-page">
        <!-- Hero Section -->
        <section class="hero-content-section bg-white p-t-60 p-b-40">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h1 class="mb-3 fw-bold text-gray-900">{{ __('messages.terms_conditions') }}</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('medical') }}">{{ __('messages.web.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.terms_conditions') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <!-- Terms Content -->
        <section class="terms-content-section bg-light py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow-sm border-0 rounded-15 p-4 p-md-5 bg-white">
                            <div class="terms-body text-gray-800 lh-lg">
                                {!! $termConditions['terms_conditions'] !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
