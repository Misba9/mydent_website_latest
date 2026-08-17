<header class='d-flex align-items-center justify-content-between flex-grow-1 header px-3 px-xl-0'>
    <button type="button" class="btn px-0 aside-menu-container__aside-menubar d-block d-xl-none sidebar-btn">
        <i class="fa-solid fa-bars fs-1"></i>
    </button>
    <nav class="navbar navbar-expand-xl navbar-light {{(Auth::check() && Auth::user()->dark_mode) ? 'bg-light' : 'bg-white' }} top-navbar d-xl-flex d-block px-3 px-xl-0 py-4 py-xl-0 "
         id="nav-header">
        <div class="container-fluid">
            <div class="navbar-collapse">
                <div class="d-flex align-items-center">
                    @if(Auth::check())
                        @if(isRole('patient'))
                            <h3 class="text-gray-900 fw-bold mb-0 me-4 fs-3">Patient Panel</h3>
                        @elseif(isRole('doctor'))
                            <h3 class="text-gray-900 fw-bold mb-0 me-4 fs-3">Doctor Panel</h3>
                        @elseif(isRole('clinic_admin'))
                            <h3 class="text-gray-900 fw-bold mb-0 me-4 fs-3">Admin Panel</h3>
                        @endif
                    @endif
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @include('layouts.sub_menu')
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <ul class="nav align-items-center">
        @impersonating
        <li class="px-xxl-3 px-2">
            <span class="text-primary">
                <a data-turbo="false" data-turbo-eval="false" href="{{ route('impersonate.leave') }}">
                    <i class="fas fa-user-check fs-2"></i>
                </a>
            </span>
        </li>
        @endImpersonating
        <li class="px-sm-3 px-2">
            @if(Auth::check() && Auth::user()->dark_mode)
                <a href="javascript:void(0)" title="Switch to Light mode"><i
                        class="fa-solid fa-moon text-primary fs-2 apply-dark-mode"></i></a>
            @else
                <a href="javascript:void(0)" title="Switch to Dark mode"><i
                        class="fa-solid fa-sun text-primary fs-2 apply-dark-mode"></i></a>
            @endif
        </li>

        @php
                $notifications = getNotification();
            @endphp
            @if(Auth::check() && (getLogInUser()->hasRole('doctor') || getLogInUser()->hasRole('patient')))
            <li class="px-sm-3 px-2">
                <div class="dropdown custom-dropdown d-flex align-items-center py-4">
                    <button class="btn dropdown-toggle hide-arrow ps-2 pe-0" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-bell text-primary fs-2"></i>
                        @if(count($notifications) != 0)
                            <span class="badge bg-primary rounded-circle position-absolute d-flex mt-2 notification-counter" id="header-notification-counter">{{ count($notifications) }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu py-0 my-2" aria-labelledby="dropdownMenuButton1">
                        <div class="text-start border-bottom py-4 px-7">
                            <h3 class="text-gray-900 mb-0">{{ __('messages.notification.notification') }}</h3>
                        </div>
                        <div class="px-7 mt-5 inner-scroll height-270">
                            @if(count($notifications) > 0)
                                @foreach($notifications as $notification)
                                    <a href="javascript:void(0)" data-id="{{ $notification->id }}"
                                       class="readNotification text-decoration-none"
                                       id="readNotification">
                                        <div class="d-flex position-relative mb-5">
                                            <span class="me-5 text-primary fs-2 icon-label"><i class="{{ getNotificationIcon($notification->type) }}"></i></span>
                                            <div>
                                                <h5 class="text-gray-900 fs-6 mb-2">{{ $notification->title }}</h5>
                                                <h6 class="text-gray-600 fs-small fw-light mb-0">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans(null, true)}}</h6>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                <div class="empty-state fs-6 text-gray-800 fw-bold text-center mt-5 d-none"
                                     data-height="400">
                                    <h5 class="text-gray-900 fs-6 mb-2">{{ __('messages.notification.you_don`t_have_any_new_notification') }}</h5>
                                </div>
                            @else
                                <div class="empty-state fs-6 text-gray-800 fw-bold text-center mt-5"
                                     data-height="400">
                                    <h5 class="text-gray-900 fs-6 mb-2">{{ __('messages.notification.you_don`t_have_any_new_notification') }}</h5>
                                </div>
                            @endif
                        </div>
                        <div class="text-center border-top p-4 {{ count($notifications) > 0 ? '' : 'd-none' }}" id="readAllNotification">
                            <a href="javascript:void(0)" class="text-decoration-none">
                                <h5 class="text-primary mb-0 fs-5">{{ __('messages.notification.mark_all_as_read') }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        @endif

        @if(Auth::check())
        <li class="px-sm-3 px-2">
            <div class="dropdown d-flex align-items-center py-2">
                @php
                    $user = getLogInUser();
                    $profileUrl = null;
                    if ($user) {
                        if ($user->hasRole('patient') && $user->patient) {
                            $profileUrl = $user->patient->profile ?? null;
                        } else {
                            $profileUrl = $user->profile_image ?? null;
                        }
                    }
                    $initial = $user ? strtoupper(substr($user->first_name, 0, 1)) : 'U';
                @endphp
                <div class="image image-circle image-mini me-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                    @if($profileUrl)
                        <img class="img-fluid rounded-circle" alt="{{ $user ? $user->full_name : 'Profile' }}"
                             src="{{ $profileUrl }}"
                             style="width: 38px; height: 38px; object-fit: cover;"
                             onerror="this.style.display='none'; if(this.nextElementSibling) { this.nextElementSibling.classList.remove('d-none'); this.nextElementSibling.classList.add('d-flex'); }" />
                        <span class="avatar-fallback rounded-circle bg-primary text-white d-none align-items-center justify-content-center fw-bold fs-6" style="width: 38px; height: 38px; min-width: 38px; line-height: 38px; text-align: center;">
                            {{ $initial }}
                        </span>
                    @else
                        <span class="avatar-fallback rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-6" style="width: 38px; height: 38px; min-width: 38px; line-height: 38px; text-align: center;">
                            {{ $initial }}
                        </span>
                    @endif
                </div>
                <button class="btn dropdown-toggle ps-1 pe-0 text-truncate text-gray-900 fw-bold border-0 bg-transparent" type="button" id="menuDropDown"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" style="max-width: 180px;">
                    <span class="d-none d-sm-inline-block">{{ $user->full_name }}</span>
                </button>
                <div class="dropdown-menu py-7 pb-4 my-2" aria-labelledby="menuDropDown">
                    <div class="text-center border-bottom pb-5">
                        <div class="image image-circle image-tiny mb-3 d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                            @if($profileUrl)
                                <img class="img-fluid rounded-circle" alt="{{ $user->full_name }}"
                                     src="{{ $profileUrl }}"
                                     style="width: 60px; height: 60px; object-fit: cover;"
                                     onerror="this.style.display='none'; if(this.nextElementSibling) { this.nextElementSibling.classList.remove('d-none'); this.nextElementSibling.classList.add('d-flex'); }" />
                                <span class="avatar-fallback rounded-circle bg-primary text-white d-none align-items-center justify-content-center fw-bold fs-3" style="width: 60px; height: 60px; min-width: 60px; line-height: 60px; text-align: center;">
                                    {{ $initial }}
                                </span>
                            @else
                                <span class="avatar-fallback rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 60px; height: 60px; min-width: 60px; line-height: 60px; text-align: center;">
                                    {{ $initial }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-gray-900 mb-1">{{ getLogInUser()->full_name }}</h3>
                        <h4 class="mb-0 fw-400 fs-6 text-muted">{{ getLogInUser()->email }}</h4>
                    </div>

                    <ul class="pt-4">
                        <li>
                            <a class="dropdown-item text-gray-900" href="{{ route('profile.setting') }}">

                            <span class="dropdown-icon me-4 text-gray-600">
                                <i class="fa-solid fa-user"></i>
                            </span>
                                {{ __('messages.user.account_setting') }}
                            </a>
                        </li>
                        @if((is_impersonating() === false))
                            <li>
                                <a class="dropdown-item text-gray-900" id="changePassword" href="javascript:void(0)">
                                    <span class="dropdown-icon me-4 text-gray-600">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    {{ __('messages.user.change_password') }}
                                </a>
                            </li>
                        @endif
                        @if(getLogInUser()->hasRole('doctor') || getLogInUser()->hasRole('patient'))
                        <li>
                            <a class="dropdown-item text-gray-900" id="emailNotification" href="javascript:void(0)">
                                    <span class="dropdown-icon me-4 text-gray-600">
                                        <i class="fa-solid fa-bell"></i>
                                    </span>
                                {{ __('messages.user.email_notification') }}
                            </a>
                        </li>
                        @endif
                        @if(session('impersonated_by'))
                        <li>
                            <a class="dropdown-item text-gray-900" href="{{ route('impersonate.leave') }}"
                               data-turbo="false">
                                    <span class="dropdown-icon me-4 text-gray-600">
                                        <i class="fa-solid fa-user-check"></i>
                                    </span>
                                {{ __('messages.user.return_to_admin') }}
                            </a>
                        </li>
                        @endif
                        <li>
                            <a class="dropdown-item text-gray-900" id="changeLanguage" href="javascript:void(0)">
                               <span class="dropdown-icon me-4 text-gray-600">
                                   <i class="fa-solid fa-globe"></i>
                               </span>
                                {{ __('messages.user.change_language') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-gray-900 d-flex" href="javascript:void(0)">
                                <span class="dropdown-icon me-4 text-gray-600">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </span>
                                <form id="logout-form" action="{{ route('logout')}}" method="post">
                                    @csrf
                                </form>
                                <span onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
                                    {{ __('messages.user.sign_out') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
        @endif
        <li>

            <button type="button" class="btn px-0 d-block d-xl-none header-btn pb-2">
                <i class="fa-solid fa-bars fs-1"></i>
            </button>
        </li>
    </ul>
</header>
<div class="bg-overlay" id="nav-overly"></div>
