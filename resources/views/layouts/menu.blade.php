@php $styleCss = 'style' @endphp
<div class="no-record text-center d-none">{{ __('messages.no_matching_records_found') }}</div>

@can('manage_admin_dashboard')
    <li class="nav-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('admin.dashboard') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-digital-tachograph"></i></span>
            <span class="aside-menu-title">{{ __('messages.dashboard') }}</span>
        </a>
    </li>
@endcan

@if (!isRole('doctor') && !isRole('patient'))
    <li class="nav-item {{ Request::is('admin/ecom*', 'admin/products*', 'admin/orders*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" data-bs-toggle="collapse" href="#ecomMenu" role="button" aria-expanded="false" aria-controls="ecomMenu">
            <span class="aside-menu-icon pe-3"><i class="fas fa-shopping-cart"></i></span>
            <span class="aside-menu-title">Ecom</span>
        </a>
        <div class="collapse {{ Request::is('admin/products*') || Request::is('admin/orders*') ? 'show' : '' }}" id="ecomMenu">
            <ul class="list-unstyled ps-4">
                <li class="{{ Request::is('admin/products*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.products.index') }}">Products</a>
                </li> 
                <li class="{{ Request::is('admin/orders*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('orders.front') }}">Orders</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item {{ Request::is('admin/blogs*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" href="{{ route('blogs.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-blog"></i></span>
            <span class="aside-menu-title">Blogs</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('admin/articles*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" href="{{ route('admin.articles.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-newspaper"></i></span>
            <span class="aside-menu-title">Articles</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('homepage-videos*', 'admin/homepage-videos*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" href="{{ route('homepage-videos.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-video"></i></span>
            <span class="aside-menu-title">Manage Videos</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('admin/main-banners*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" href="{{ route('main-banners.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-image"></i></span>
            <span class="aside-menu-title">Manage Banners</span>
        </a>
    </li>
@endif

@role('doctor')
    <li class="nav-item {{ Request::is('doctors/dashboard*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('doctors.dashboard') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-digital-tachograph"></i></span>
            <span class="aside-menu-title">{{ __('messages.dashboard') }}</span>
        </a>
    </li>
    @can('manage_appointments')
    <li class="nav-item {{ Request::is('doctors/appointments*', 'doctors/patient*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('doctors.appointments') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-calendar-alt"></i></span>
            <span class="aside-menu-title">{{ __('messages.appointment.appointments') }}</span>
        </a>
    </li>
    @endcan
    @can('manage_transactions')
    <li class="nav-item {{ Request::is('doctors/transactions*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('doctors.transactions') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-money-bill-wave"></i></span>
            <span class="aside-menu-title">{{ __('messages.transactions') }}</span>
        </a>
    </li>
    @endcan
    <li class="nav-item {{ Request::is('doctors/doctor-schedule-edit*', 'doctors/doctor-sessions/create') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ getLoginDoctorSessionUrl() }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-calendar"></i></span>
            <span class="aside-menu-title">{{ __('messages.doctor_session.my_schedule') }}</span>
        </a>
    </li>
    @can('manage_patient_visits')
    <li class="nav-item {{ Request::is('doctors/visits*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('doctors.visits.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-procedures"></i></span>
            <span class="aside-menu-title">{{ __('messages.visits') }}</span>
        </a>
    </li>
    @endcan
    <li class="nav-item {{ Request::is('doctors/live-consultations*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('doctors.live-consultations.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-video"></i></span>
            <span class="aside-menu-title">{{ __('messages.live_consultations') }}</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('doctors/connect-google-calendar*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('doctors.googleCalendar.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-calendar-day"></i></span>
            <span class="aside-menu-title">{{ __('messages.setting.connect_google_calendar') }}</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('doctors/holidays*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('doctors.holiday') }}">
            <span class="aside-menu-icon pe-3"><i class="fa-solid fa-calendar-xmark"></i></span>
            <span class="aside-menu-title">{{ __('messages.holiday.holiday') }}</span>
        </a>
    </li>
@endrole

@role('patient')
    <li class="nav-item {{ Request::is('patients/dashboard*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.dashboard') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-digital-tachograph"></i></span>
            <span class="aside-menu-title">Dashboard</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('patients/appointments*') && !Request::is('patients/patient-appointments-calendar*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.patient-appointments-index') }}" data-turbo="false">
            <span class="aside-menu-icon pe-3"><i class="fas fa-calendar-alt"></i></span>
            <span class="aside-menu-title">My Appointments</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('patients/patient-appointments-calendar*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.appointments.calendar') }}" data-turbo="false">
            <span class="aside-menu-icon pe-3"><i class="fas fa-calendar-check"></i></span>
            <span class="aside-menu-title">Appointments Calendar</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('patients/patient-visits*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.patient.visits.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-procedures"></i></span>
            <span class="aside-menu-title">My Clinic Visits</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('patients/transactions*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.transactions') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-money-bill-wave"></i></span>
            <span class="aside-menu-title">Billing / Transactions</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('patients/live-consultation*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.live-consultations.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-video"></i></span>
            <span class="aside-menu-title">Live Consultations</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('patients/reviews*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.reviews.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-star"></i></span>
            <span class="aside-menu-title">Reviews & Feedback</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('patients/connect-google-calendar*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.googleCalendar.index') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-calendar-day"></i></span>
            <span class="aside-menu-title">Connect Google Calendar</span>
        </a>
    </li>

    <li class="nav-item {{ Request::is('profile/edit*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('profile.setting') }}">
            <span class="aside-menu-icon pe-3"><i class="fas fa-user-cog"></i></span>
            <span class="aside-menu-title">Account / Profile</span>
        </a>
    </li>
@endrole

@if (!isRole('doctor') && !isRole('patient'))
    @can('manage_doctors')
        <li class="nav-item {{ Request::is('admin/doctors*', 'doctors/doctor-sessions*', 'admin/doctor-sessions*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('doctors.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fa-solid fa-user-doctor"></i></span>
                <span class="aside-menu-title">{{ __('messages.doctors') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_patients')
        <li class="nav-item {{ Request::is('admin/patients*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('patients.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-hospital-user"></i></span>
                <span class="aside-menu-title">{{ __('messages.patients') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_appointments')
        <li class="nav-item {{ Request::is('admin/appointments*', 'admin/admin-appointments-calendar*', 'admin/prescriptions*', 'admin/prescription-medicine-show*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('appointments.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-calendar-alt"></i></span>
                <span class="aside-menu-title">{{ __('messages.appointments') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_medicines')
        <li class="nav-item {{ Request::is('admin/categories*', 'admin/brands*', 'admin/medicines*', 'admin/medicine-purchase*', 'admin/used-medicine*', 'admin/medicine-bills*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('categories.index') }}">
                <span class="aside-menu-icon me-3"><i class="fas fa-capsules"></i></span>
                <span class="aside-menu-title">{{ __('messages.medicines') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_transactions')
        <li class="nav-item {{ Request::is('admin/transactions*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('transactions') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-money-bill-wave"></i></span>
                <span class="aside-menu-title">{{ __('messages.transactions') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_patient_visits')
        <li class="nav-item {{ Request::is('admin/visits*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('visits.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-procedures"></i></span>
                <span class="aside-menu-title">{{ __('messages.visits') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_services')
        <li class="nav-item {{ Request::is('admin/services*', 'admin/service-categories*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('services.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-user-cog"></i></span>
                <span class="aside-menu-title">{{ __('messages.services') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_specialities')
        <li class="nav-item {{ Request::is('admin/specializations*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('specializations.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-user-shield"></i></span>
                <span class="aside-menu-title">{{ __('messages.specializations') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_front_cms')
        <li class="nav-item {{ Request::is('admin/enquiries*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('enquiries.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-question-circle"></i></span>
                <span class="aside-menu-title">{{ __('messages.enquiries') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Request::is('admin/cms*', 'admin/sliders*', 'admin/faqs*', 'admin/front-medical-services*', 'admin/front-patient-testimonials*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('cms.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-tasks"></i></span>
                <span class="aside-menu-title">{{ __('messages.front_cms') }}</span>
            </a>
        </li>
    @endcan
    @can('manage_settings')
        <li class="nav-item {{ Request::is('admin/settings*', 'admin/roles*', 'admin/currencies*', 'admin/clinic-schedules*', 'admin/countries*', 'admin/states*', 'admin/cities*', 'admin/holidays*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center py-4" aria-current="page" href="{{ route('setting.index') }}">
                <span class="aside-menu-icon pe-3"><i class="fas fa-cogs"></i></span>
                <span class="aside-menu-title">{{ __('messages.settings') }}</span>
            </a>
        </li>
    @endcan
@endif
