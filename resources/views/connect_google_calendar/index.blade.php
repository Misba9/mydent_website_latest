@extends('layouts.app')
@section('title')
    {{ __('messages.setting.connect_google_calendar') }}
@endsection
@section('content')
    <div class="container-fluid py-4">
        @include('flash::message')
        <div class="d-flex flex-column">
            {{Form::hidden('doctor_role',getLogInUser()->hasRole('doctor'),['id' => 'googleCalendarDoctorRole'])}}
            {{Form::hidden('patient_role',getLogInUser()->hasRole('patient'),['id' => 'googleCalendarPatientRole'])}}
            
            @if(getLogInUser()->hasRole('doctor'))
                @if(!isset($data['checkTimeZone']->time_zone))
                    <div class="mb-5">
                        <div class="d-flex align-items-center rounded-3 py-3 px-4 bg-light-danger border border-danger border-opacity-25 shadow-xs">
                            <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor"></rect>
                                    <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
                                </svg>
                            </span>
                            <div class="text-danger fw-semibold fs-6">
                                <strong>Note:</strong> Please select your timezone in your profile settings before connecting Google Calendar. Default UTC will be used if unset.
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if(getLogInUser()->hasRole('patient'))
                <div class="mb-5">
                    <div class="d-flex align-items-center rounded-3 py-3 px-4 bg-light-danger border border-danger border-opacity-25 shadow-xs">
                        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                                <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor"></rect>
                                <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
                            </svg>
                        </span>
                        <div class="text-danger fw-semibold fs-6">
                            <strong>Note:</strong> We use the doctor's timezone when creating appointments in the calendar. If a doctor has not set a timezone, UTC will be used by default.
                        </div>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-15 mb-6">
                @if(!$data['googleCalendarIntegrationExists'])
                    <div class="card-body text-center p-6 p-md-10">
                        <div class="mb-4">
                            <i class="fa-solid fa-calendar-days text-primary" style="font-size: 56px;"></i>
                        </div>
                        <h3 class="text-gray-900 fw-bold mb-3">Sync Your Appointments</h3>
                        <p class="text-gray-600 fs-6 mb-6 mx-auto" style="max-width: 520px;">
                            Connect your Google Calendar to automatically synchronize all your booked appointments with your personal schedule.
                        </p>
                        <div class="d-flex justify-content-center">
                            @if(getLogInUser()->hasRole('doctor'))
                                @if(!isset($data['checkTimeZone']->time_zone))
                                    <a href="{{ route('googleAuth') }}"
                                       class="btn btn-primary btn-lg rounded-pill px-7 py-3 fw-bold disabled">{{ __('messages.setting.connect_your_google_calendar') }}</a>
                                @else
                                    <a href="{{ route('googleAuth') }}"
                                       class="btn btn-primary btn-lg rounded-pill px-7 py-3 fw-bold shadow-sm">{{ __('messages.setting.connect_your_google_calendar') }}</a>
                                @endif
                            @else
                                <a href="{{ route('googleAuth') }}"
                                   class="btn btn-primary btn-lg rounded-pill px-7 py-3 fw-bold shadow-sm">{{ __('messages.setting.connect_your_google_calendar') }}</a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card-header border-bottom py-4">
                        <div class="card-title m-0 d-flex align-items-center">
                            <span class="fs-5 fw-bold text-gray-900 me-2">{{ __('messages.setting.select_your_calendars_from_google_calendar_in_which_you_want_to_create_the_appointments') }}.</span>
                            @if(getLogInUser()->hasRole('doctor'))
                                <span data-bs-toggle="tooltip" title="{{ __('messages.setting.when_patient_book_an_appointment_with_you_new_appointment_will_created_on_selected_calendars') }}.">
                                    <i class="fa fa-question-circle text-muted"></i>
                                </span>
                            @elseif(getLogInUser()->hasRole('patient'))
                                <span data-bs-toggle="tooltip" title="{{ __('messages.setting.when_you_book_an_appointment_new_appointment_will_created_on_selected_calendars') }}.">
                                    <i class="fa fa-question-circle text-muted"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    {{ Form::open(['id' => 'googleCalendarForm']) }}
                    <div class="card-body p-6 p-md-8">
                        @foreach($data['googleCalendarLists'] as $googleCalendarList)
                            <div class="form-check form-check-custom form-check-solid d-flex align-items-center mb-4">
                                <div class="fv-row d-flex align-items-center me-3">
                                    {{ Form::checkbox('google_calendar[]', $googleCalendarList->id, \App\Models\AppointmentGoogleCalendar::whereGoogleCalendarListId($googleCalendarList->id)->exists(), ['class' => 'form-check-input me-3 google-calendar']) }}
                                </div>
                                <label class="col-form-label fw-bold fs-6 text-gray-800 cursor-pointer">
                                    <span>{{ $googleCalendarList->calendar_name }}</span>
                                </label>
                            </div>
                        @endforeach
                        <div class="pt-4 mt-4 border-top">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit"
                                        class="btn btn-primary rounded-pill px-6">{{ __('messages.common.save') }}</button>
                                <a id="syncGoogleCalendar" class="btn btn-outline-primary rounded-pill px-6">
                                    {{ __('messages.setting.sync_your_google_calendar') }}
                                </a>
                                @if(getLogInUser()->hasRole('doctor'))
                                    <a href="{{ route('doctors.disconnectCalendar.destroy') }}"
                                       class="btn btn-danger rounded-pill px-6">{{ __('messages.setting.disconnect_your_google_calendar') }}</a>
                                @elseif(getLogInUser()->hasRole('patient'))
                                    <a href="{{ route('patients.disconnectCalendar.destroy') }}"
                                       class="btn btn-danger rounded-pill px-6">{{ __('messages.setting.disconnect_your_google_calendar') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                @endif
            </div>
        </div>
    </div>
@endsection
