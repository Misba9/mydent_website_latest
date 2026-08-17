<header class="position-relative header shadow-xs bg-white py-2">
    <div class="container-fluid px-lg-4">
        <div class="row align-items-center">
            <div class="col-lg-2 col-6">
                <a href="{{ url('/') }}" class="header-logo d-inline-block">
                    <img src="{{ asset(getAppLogo()) }}" 
                         alt="MyDent"
                         class="front-app-logo img-fluid" 
                         style="max-height: 48px; width: auto; object-fit: contain;"
                         onerror="this.src='{{ asset('storage/imgs/logo.png') }}'" />

                </a> 
            </div>
            <div class="col-lg-10 col-6">
                <nav class="navbar navbar-expand-lg navbar-light justify-content-end py-0">
                    <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav align-items-center py-2 py-lg-0 me-lg-3">
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('/') ? 'active fw-bold text-primary' : '' }}" aria-current="page" href="{{ url('/') }}">{{ __('messages.web.home') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-doctors*') ? 'active fw-bold text-primary' : '' }}"
                                   href="{{ route('medicalDoctors') }}">Aligners</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-services*') ? 'active fw-bold text-primary' : '' }}"
                                   href="{{ route('medicalServices') }}">Smileverse</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-contact*') ? 'active fw-bold text-primary' : '' }}"
                                   href="{{ route('medicalContact') }}"
                                   data-turbo="false">Center</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('products*') || Request::is('ecom*') ? 'active fw-bold text-primary' : '' }}"
                                   href="{{ route('products.index') }}"
                                   data-turbo="false">Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-about-us*') ? 'active fw-bold text-primary' : '' }}"
                                   href="{{ route('medicalAboutUs') }}">Why MyDent?</a>
                            </li>
                        </ul>
                        <div class="header-btn-grp d-flex align-items-center gap-2">
                            @if(getLogInUser())
                                @if(getLogInUser()->hasRole('doctor'))
                                    <a href="{{ route('doctors.dashboard') }}"
                                       class="btn btn-sm btn-outline-primary" data-turbo="false">Dashboard</a>
                                @elseif(getLogInUser()->hasRole('patient'))
                                    <a href="{{ route('patients.dashboard') }}"
                                       class="btn btn-sm btn-outline-primary" data-turbo="false">Dashboard</a>
                                @else
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="btn btn-sm btn-outline-primary" data-turbo="false">Dashboard</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="btn btn-sm btn-outline-primary" data-turbo="false">{{ __('messages.login') }}</a>
                            @endif
                            <a href="{{ route('medicalAppointment') }}" class="btn btn-sm btn-primary px-3">Book</a>
                            <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-secondary position-relative">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ session('cart') ? count(session('cart')) : 0 }}
                                </span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>