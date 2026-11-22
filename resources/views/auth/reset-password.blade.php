@extends('layouts.auth')
@section('title')
    Reset Password
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid align-items-center justify-content-center p-4">
        <div class="col-12 text-center">
            <a href="{{ route('medical') }}" class="image mb-7 mb-sm-10">
                <img alt="Logo" src="{{ asset(getAppLogo()) }}" class="img-fluid logo-fix-size" style="width:90px;">
            </a>
        </div>
        <div class="width-540">
            @include('flash::message')
            @include('layouts.errors')
        </div>
        <div class="bg-white rounded-15 shadow-md width-540 px-5 px-sm-7 py-10 mx-auto">
            <h1 class="text-center mb-7">{{__('auth.sign_in')}}</h1>
            <form method="POST" action="{{ route('password.update') }}" id="">
            @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <!--Email-->
                <div class="mb-sm-7 mb-4">
                    <label for="email" class="form-label">
                        {{ __('messages.user.email').':' }}<span class="required"></span>
                    </label>
                    <input id="email" class="form-control form-control-solid" type="email"
                           value="{{ old('email', $request->email) }}"
                           required autofocus name="email" autocomplete="off"
                           placeholder="{{__('messages.web.email')}}"/>
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                </div>

                {{--Password--}}
                <div class="mb-sm-7 mb-4">
                    <label class="form-label"
                           for="password">{{__('messages.patient.password')}}</label>
                    <div class="md-password-field mb-3">
                        <input id="password" class="form-control form-control-solid"
                               type="password"
                               name="password"
                               required autocomplete="new-password"/>
                        <button type="button" class="md-password-toggle" data-target="#password" aria-label="Show password" aria-pressed="false">
                            <svg class="md-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"/><circle cx="12" cy="12" r="2.75"/></svg>
                            <svg class="md-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.6a2.75 2.75 0 003.9 3.9M9.4 5.5A10.3 10.3 0 0112 5.25c6 0 9.75 6.75 9.75 6.75a17.7 17.7 0 01-3.2 3.7M6.1 6.3A17.4 17.4 0 002.25 12S6 18.75 12 18.75c1.1 0 2.14-.2 3.12-.55"/></svg>
                        </button>
                    </div>
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="fv-row mb-5">
                    <label class="form-label "
                           for="password_confirmation">{{__('messages.patient.confirm_password')}}</label>
                    <div class="md-password-field">
                        <input class="form-control form-control-solid" type="password"
                               id="password_confirmation" name="password_confirmation" autocomplete="new-password"/>
                        <button type="button" class="md-password-toggle" data-target="#password_confirmation" aria-label="Show password" aria-pressed="false">
                            <svg class="md-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"/><circle cx="12" cy="12" r="2.75"/></svg>
                            <svg class="md-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.5 10.6a2.75 2.75 0 003.9 3.9M9.4 5.5A10.3 10.3 0 0112 5.25c6 0 9.75 6.75 9.75 6.75a17.7 17.7 0 01-3.2 3.7M6.1 6.3A17.4 17.4 0 002.25 12S6 18.75 12 18.75c1.1 0 2.14-.2 3.12-.55"/></svg>
                        </button>
                    </div>
                    <div class="invalid-feedback">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">{{ __('Reset Password') }}</span>
                    </button>
                </div>
            </form>
        </div>
@endsection
