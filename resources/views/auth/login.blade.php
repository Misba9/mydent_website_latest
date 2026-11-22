@extends('layouts.auth')
@section('title')
    {{__('messages.login')}}
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid align-items-center justify-content-center p-4">
        <div class="col-12 text-center">
            <a href="{{ route('medical') }}" class="image mb-7 mb-sm-10">
                <img alt="MyDent"
                     src="{{ asset('assets/image/mydent-logo-transparent.png') }}"
                     class="img-fluid"
                     style="width:140px; height:auto; object-fit:contain;"
                     onerror="{!! imgOnErrorFallback('assets/image/mydent-logo-transparent.png') !!}">
            </a>
        </div>
        <div class="width-540">
            @include('flash::message')
            @include('layouts.errors')
        </div>
        <div class="bg-white rounded-15 shadow-md width-540 px-5 px-sm-7 py-10 mx-auto">
            <h1 class="text-center mb-7">{{__('auth.sign_in')}}</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-sm-7 mb-4">
                    <label for="email" class="form-label">
                        {{ __('messages.patient.email').':' }}<span class="required"></span>
                    </label>
                    <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" required placeholder="Enter Email">
                </div>

                <div class="mb-sm-7 mb-4">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label">{{ __('messages.patient.password') .':' }}<span
                                    class="required"></span></label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link-info fs-6 text-decoration-none">
                                {{ __('messages.common.forgot_your_password').'?' }}
                            </a>
                        @endif
                    </div>
                    <div class="md-password-field">
                        <input name="password" type="password" class="form-control" id="password" required placeholder="Enter Password" autocomplete="current-password">
                        <button type="button" class="md-password-toggle" data-target="#password" aria-label="Show password" aria-pressed="false" tabindex="0">
                            <svg class="md-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"/>
                                <circle cx="12" cy="12" r="2.75"/>
                            </svg>
                            <svg class="md-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.6a2.75 2.75 0 003.9 3.9M9.4 5.5A10.3 10.3 0 0112 5.25c6 0 9.75 6.75 9.75 6.75a17.7 17.7 0 01-3.2 3.7M6.1 6.3A17.4 17.4 0 002.25 12S6 18.75 12 18.75c1.1 0 2.14-.2 3.12-.55"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-sm-7 mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember_me">
                    <label class="form-check-label" for="remember_me">{{ __('messages.common.remember_me') }}</label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">{{ __('messages.login') }}</button>
                </div>

                <div class="d-flex align-items-center mb-10 mt-4">
                    <span class="text-gray-700 me-2">{{__('messages.web.new_here').'?'}}</span>
                    <a href="{{ route('register') }}" class="link-info fs-6 text-decoration-none">
                        {{__('messages.web.create_an_account')}}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
