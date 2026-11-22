@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical_appointment') }}
@endsection
@section('front-content')
    <div class="book-appointment-page md-book-wrap">
        <x-front.page-hero
            title="Book your 3D scan"
            lead="Begin your smile transformation. Share a few details and our team will guide your personalized plan."
            image="images/3d-dental-scan.jpg"
        />

        <section class="md-section md-section--soft">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="appointment-form form-wraper">
                            <form class="book-appointment-form bg-white p-4 p-md-5" id="frontAppointmentBook" action="{{ route('front.appointment.book') }}" method="post">
                                @csrf
                                @include('fronts.common.book_appointment')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
