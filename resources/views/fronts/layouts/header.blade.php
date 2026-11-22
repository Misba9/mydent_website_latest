<header class="md-header" id="mdHeader">
    <div class="container py-2">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <a href="{{ url('/') }}" class="header-logo d-inline-flex align-items-center flex-shrink-0">
                <img src="{{ asset('assets/image/mydent-logo-transparent.png') }}"
                     alt="MyDent Clear Aligners"
                     class="front-app-logo img-fluid"
                     width="200"
                     height="68"
                     onerror="{!! imgOnErrorFallback('assets/image/mydent-logo-transparent.png') !!}" />
            </a>

            <nav class="md-primary-nav d-none d-lg-flex align-items-center gap-1" aria-label="Primary">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                <a class="nav-link {{ Request::is('medical-doctors*') ? 'active' : '' }}" href="{{ route('medicalDoctors') }}">Aligners</a>
                <a class="nav-link {{ Request::is('medical-services*') ? 'active' : '' }}" href="{{ route('medicalServices') }}">Smileverse</a>
                <a class="nav-link {{ Request::is('medical-contact*') ? 'active' : '' }}" href="{{ route('medicalContact') }}" data-turbo="false">Center</a>
                <a class="nav-link {{ Request::is('products*') || Request::is('ecom*') ? 'active' : '' }}" href="{{ route('products.index') }}" data-turbo="false">Products</a>
                <a class="nav-link text-nowrap {{ Request::is('medical-about-us*') ? 'active' : '' }}" href="{{ route('medicalAboutUs') }}">Why MyDent?</a>
            </nav>

            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <div class="d-none d-lg-flex align-items-center gap-2">
                    @if(getLogInUser())
                        @if(getLogInUser()->hasRole('doctor'))
                            <x-front.button href="{{ route('doctors.dashboard') }}" variant="outline" size="sm" data-turbo="false">Dashboard</x-front.button>
                        @elseif(getLogInUser()->hasRole('patient'))
                            <x-front.button href="{{ route('patients.dashboard') }}" variant="outline" size="sm" data-turbo="false">Dashboard</x-front.button>
                        @else
                            <x-front.button href="{{ route('admin.dashboard') }}" variant="outline" size="sm" data-turbo="false">Dashboard</x-front.button>
                        @endif
                    @else
                        <x-front.button href="{{ route('login') }}" variant="outline" size="sm" data-turbo="false">Login</x-front.button>
                    @endif
                    <x-front.button href="{{ route('medicalAppointment') }}" size="sm">Book Appointment</x-front.button>
                </div>

                <a href="{{ route('cart.index') }}" class="md-cart-btn" aria-label="Shopping cart">
                    <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </a>

                <button class="navbar-toggler d-lg-none"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#mdMobileNav"
                        aria-controls="mdMobileNav"
                        aria-label="Open menu">
                    <i class="fas fa-bars" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</header>
