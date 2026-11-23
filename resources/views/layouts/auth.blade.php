<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') | {{ getAppName() }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset(getAppFavicon()) }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <!-- General CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    @php
        try {
            $mdPagesCssHref = mix('assets/css/pages.css');
        } catch (\Throwable $e) {
            $mdPagesCssHref = asset('assets/css/pages.css');
        }
    @endphp
    <link rel="stylesheet" type="text/css" href="{{ $mdPagesCssHref }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <style>
        :root { --bs-primary: #E83D45; --bs-primary-rgb: 232, 61, 69; }
        body { font-family: Inter, system-ui, sans-serif; background: #F5F9FA; }
        h1, h2, h3 { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .btn-primary { background: #E83D45; border-color: #E83D45; border-radius: 999px; font-weight: 600; }
        .btn-primary:hover { background: #C92F37; border-color: #C92F37; }
        .bg-white.rounded-15, .width-540 > .bg-white { border-radius: 1.25rem !important; border: 1px solid #E7EEF0; box-shadow: 0 10px 30px rgba(24,43,53,.08); }
        .form-control { border-radius: .65rem; min-height: 46px; border-color: #E7EEF0; }
        .form-control:focus { border-color: #76B9C8; box-shadow: 0 0 0 .2rem rgba(118,185,200,.25); }
        .link-info { color: #E83D45 !important; }
        .md-password-field { position: relative; }
        .md-password-field .form-control { padding-right: 3rem; }
        .md-password-toggle {
            position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
            border: 0; background: transparent; padding: .25rem; line-height: 0;
            color: #6B7C85; cursor: pointer; border-radius: .35rem;
        }
        .md-password-toggle:hover, .md-password-toggle:focus { color: #E83D45; outline: none; }
        .md-password-toggle svg { width: 1.15rem; height: 1.15rem; display: block; }
        .md-password-toggle .md-eye-off { display: none; }
        .md-password-toggle.is-visible .md-eye { display: none; }
        .md-password-toggle.is-visible .md-eye-off { display: block; }
    </style>

    <!-- CSS Libraries -->
    @stack('css')
</head>

<body
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed">
<div class="d-flex flex-column flex-root">
    <div
        class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed authImage">
        @yield('content')
    </div>
</div>

<footer>
    <div class="container-fluid padding-0">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-6">
                <div class="copyright text-center text-muted">
                    {{ __('messages.all_rights_reserved') }} &copy; {{ date('Y') }} <a href="/"
                                                                  class="font-weight-bold ml-1"
                                                                      target="_blank">{{ getAppName() }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="{{ asset('backend/js/vendor.js') }}"></script>
    <script src="{{ mix('assets/js/auto_fill/auto_fill.js') }}"></script>
    <script src="{{ mix('js/third-party.js') }}"></script>
    @stack('scripts')
    <script>
    $(document).ready(function () {
        $('.alert').delay(5000).slideUp(300);

        $(document).on('click', '.md-password-toggle', function () {
            var $btn = $(this);
            var target = $btn.attr('data-target') || $btn.data('target');
            var $input = target ? $(target) : $btn.closest('.md-password-field').find('input');
            if (!$input.length) return;

            var show = $input.attr('type') === 'password';
            $input.attr('type', show ? 'text' : 'password');
            $btn.toggleClass('is-visible', show);
            $btn.attr('aria-label', show ? 'Hide password' : 'Show password');
            $btn.attr('aria-pressed', show ? 'true' : 'false');
        });
    });
</script>
</body>
</html>

