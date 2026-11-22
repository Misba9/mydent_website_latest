{{-- Must be a direct child of <body>, not inside .md-header (backdrop-filter traps fixed layers). --}}
<div class="offcanvas offcanvas-end md-mobile-nav"
     tabindex="-1"
     id="mdMobileNav"
     aria-labelledby="mdMobileNavLabel">
    <div class="offcanvas-header border-bottom">
        <h2 class="offcanvas-title h5 mb-0" id="mdMobileNavLabel">Menu</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column gap-1">
        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
        <a class="nav-link {{ Request::is('medical-doctors*') ? 'active' : '' }}" href="{{ route('medicalDoctors') }}">Aligners</a>
        <a class="nav-link {{ Request::is('medical-services*') ? 'active' : '' }}" href="{{ route('medicalServices') }}">Smileverse</a>
        <a class="nav-link {{ Request::is('medical-contact*') ? 'active' : '' }}" href="{{ route('medicalContact') }}" data-turbo="false">Center</a>
        <a class="nav-link {{ Request::is('products*') || Request::is('ecom*') ? 'active' : '' }}" href="{{ route('products.index') }}" data-turbo="false">Products</a>
        <a class="nav-link {{ Request::is('medical-about-us*') ? 'active' : '' }}" href="{{ route('medicalAboutUs') }}">Why MyDent?</a>
        <hr class="my-2">
        @if(getLogInUser())
            @if(getLogInUser()->hasRole('doctor'))
                <x-front.button href="{{ route('doctors.dashboard') }}" variant="outline" data-turbo="false">Dashboard</x-front.button>
            @elseif(getLogInUser()->hasRole('patient'))
                <x-front.button href="{{ route('patients.dashboard') }}" variant="outline" data-turbo="false">Dashboard</x-front.button>
            @else
                <x-front.button href="{{ route('admin.dashboard') }}" variant="outline" data-turbo="false">Dashboard</x-front.button>
            @endif
        @else
            <x-front.button href="{{ route('login') }}" variant="outline" data-turbo="false">Login</x-front.button>
        @endif
        <x-front.button href="{{ route('medicalAppointment') }}" class="mt-1">Book Appointment</x-front.button>
    </div>
</div>
