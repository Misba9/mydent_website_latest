<header class="md-header">
    <div class="container-fluid px-lg-4 py-2">
        <div class="row align-items-center">
            <div class="col-lg-2 col-6">
                <a href="{{ url('/') }}" class="header-logo d-inline-block">
                    <img src="{{ asset(getAppLogo()) }}"
                         alt="mydent"
                         class="front-app-logo img-fluid"
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
                                <a class="nav-link px-2 {{ Request::is('/') ? 'active fw-bold' : '' }}"
                                   aria-current="page" href="{{ url('/') }}">{{ __('messages.web.home') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-doctors*') ? 'active fw-bold' : '' }}"
                                   href="{{ route('medicalDoctors') }}">Aligners</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-services*') ? 'active fw-bold' : '' }}"
                                   href="{{ route('medicalServices') }}">Smileverse</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-contact*') ? 'active fw-bold' : '' }}"
                                   href="{{ route('medicalContact') }}"
                                   data-turbo="false">Center</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('products*') || Request::is('ecom*') ? 'active fw-bold' : '' }}"
                                   href="{{ route('products.index') }}"
                                   data-turbo="false">Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-about-us*') ? 'active fw-bold' : '' }}"
                                   href="{{ route('medicalAboutUs') }}">Why MyDent?</a>
                            </li>
                        </ul>
                        <div class="header-btn-grp d-flex align-items-center gap-2">
                            @if(getLogInUser())
                                @if(getLogInUser()->hasRole('doctor'))
                                    <x-front.button href="{{ route('doctors.dashboard') }}" variant="outline" size="sm" data-turbo="false">Dashboard</x-front.button>
                                @elseif(getLogInUser()->hasRole('patient'))
                                    <x-front.button href="{{ route('patients.dashboard') }}" variant="outline" size="sm" data-turbo="false">Dashboard</x-front.button>
                                @else
                                    <x-front.button href="{{ route('admin.dashboard') }}" variant="outline" size="sm" data-turbo="false">Dashboard</x-front.button>
                                @endif
                            @else
                                <x-front.button href="{{ route('login') }}" variant="outline" size="sm" data-turbo="false">{{ __('messages.login') }}</x-front.button>
                            @endif
                            <x-front.button href="{{ route('medicalAppointment') }}" size="sm" class="px-3">Book</x-front.button>
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
