<header class="position-relative header">
    <div class="container-fluid px-lg-4">
        <div class="row align-items-center">
            
            
            <div class="col-lg-1 col-3 pe-0">
    <a href="#!" class="header-logo">
        <img src="{{ asset('storage/imgs/logo.png') }}" 
             alt="mydent"
             class="object-cover front-app-logo img-fluid" 
             style="width: 400px; height: 380px;object-fit:cover" />
    </a> 
</div>

            
            
            
            
            <div class="col-lg-11 col-9" >
                <nav class="navbar navbar-expand-lg navbar-light justify-content-end py-0">
                    <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav align-items-center py-2 py-lg-0 flex-nowrap">
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('/*') ? 'active' : '' }}" aria-current="page" href="{{ url('/') }}">{{ __('messages.web.home') }}</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-doctors*') ? 'active' : '' }}"
                                   href="{{ route('medicalDoctors') }}">Aligners</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-services*') ? 'active' : '' }}"
                                   href="{{ route('medicalServices') }}">Smileverse</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-contact*') ? 'active' : '' }}"
                                   href="{{ route('medicalContact') }}"
                                   data-turbo="false">Center</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('ecom*') ? 'active' : '' }}"
                                   href="{{ route('ecom') }}"
                                   data-turbo="false">Products</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link px-2 {{ Request::is('medical-about-us*') ? 'active' : '' }}"
                                   href="{{ route('medicalAboutUs') }}">Why mydent?</a>
                            </li>
                        </ul>
                        <div class="header-btn-grp ms-lg-2 d-flex flex-nowrap">
                            @if(getLogInUser())
                                @if(getLogInUser()->hasRole('doctor'))
                                    <a href="{{ route('doctors.dashboard') }}"
                                       class="btn btn-sm btn-outline-primary me-2" data-turbo="false">Dashboard</a>
                                @elseif(getLogInUser()->hasRole('patient'))
                                    <a href="{{ route('patients.dashboard') }}"
                                       class="btn btn-sm btn-outline-primary me-2" data-turbo="false">Dashboard</a>
                                @else
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="btn btn-sm btn-outline-primary me-2" data-turbo="false">Dashboard</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="btn btn-sm btn-outline-primary me-2" data-turbo="false">{{ __('messages.login') }}</a>
                            @endif
                            <a href="{{ route('medicalAppointment') }}" class="btn btn-sm btn-primary me-2">Book</a>
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