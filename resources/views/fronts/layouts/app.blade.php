<!DOCTYPE html>
<html dir="ltr" lang="en-US" class="md-js md-page-loading">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="{{ getAppName() }}" />
    <link rel="icon" href="{{ asset(getAppFavicon()) }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script>document.documentElement.classList.add('md-js','md-page-loading');</script>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'MyDent offers premium quality invisible clear aligners and modern dental solutions. Order aligners online, consult with certified orthodontists, and get a beautiful smile comfortably.')" />
    <meta name="keywords" content="@yield('meta_keywords', 'clear aligners, invisible braces, teeth straightening, dental clinic, dental care, MyDent aligners, orthodontist Hyderabad, orthodontist Bangalore')" />
    <link rel="canonical" href="@yield('canonical_url', request()->url())" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="@yield('canonical_url', request()->url())" />
    <meta property="og:title" content="@yield('front-title') | {{ getAppName() }}" />
    <meta property="og:description" content="@yield('meta_description', 'MyDent offers premium quality invisible clear aligners and modern dental solutions. Order aligners online, consult with certified orthodontists, and get a beautiful smile comfortably.')" />
    <meta property="og:image" content="@yield('meta_og_image', asset(getAppLogo()))" />
    <meta property="og:site_name" content="{{ getAppName() }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="@yield('canonical_url', request()->url())" />
    <meta property="twitter:title" content="@yield('front-title') | {{ getAppName() }}" />
    <meta property="twitter:description" content="@yield('meta_description', 'MyDent offers premium quality invisible clear aligners and modern dental solutions. Order aligners online, consult with certified orthodontists, and get a beautiful smile comfortably.')" />
    <meta property="twitter:image" content="@yield('meta_og_image', asset(getAppLogo()))" />

    <meta name="robots" content="index, follow" />

    <!-- JSON-LD Structured Schema Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Dentist",
      "name": "MyDent Clear Aligners & Dental Clinic",
      "image": "{{ asset(getAppLogo()) }}",
      "url": "https://mydent.in",
      "telephone": "9381590963",
      "priceRange": "$$",
      "address": [
        {
          "@type": "PostalAddress",
          "streetAddress": "mydent, H.No 8, No:36 Pillar, 2-293/82/a/787, Road, Jubilee Hills",
          "addressLocality": "Hyderabad",
          "addressRegion": "Telangana",
          "postalCode": "500033",
          "addressCountry": "IN"
        },
        {
          "@type": "PostalAddress",
          "streetAddress": "Dhanvantari 2B 80 ft road KHB colony, 2nd stage Basaveshwarnagar",
          "addressLocality": "Bengaluru",
          "addressRegion": "Karnataka",
          "postalCode": "560079",
          "addressCountry": "IN"
        }
      ],
      "sameAs": [
        "https://www.facebook.com/mydent.in",
        "https://www.instagram.com/mydent.in"
      ]
    }
    </script>

    <!-- Google Fonts — Plus Jakarta Sans (display) + Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @php
        try { $mdFrontThirdPartyCss = mix('css/front-third-party.css'); } catch (\Throwable $e) { $mdFrontThirdPartyCss = asset('css/front-third-party.css'); }
        try { $mdFrontPagesCss = mix('css/front-pages.css'); } catch (\Throwable $e) { $mdFrontPagesCss = asset('css/front-pages.css'); }
        try { $mdFrontThirdPartyJs = mix('js/front-third-party.js'); } catch (\Throwable $e) { $mdFrontThirdPartyJs = asset('js/front-third-party.js'); }
        try { $mdFrontPagesJs = mix('js/front-pages.js'); } catch (\Throwable $e) { $mdFrontPagesJs = asset('js/front-pages.js'); }
    @endphp
    <link href="{{ $mdFrontThirdPartyCss }}" rel="stylesheet" type="text/css">
    <link href="{{ $mdFrontPagesCss }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/front/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/front/responsive.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/css/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/intlTelInput.css') }}">
    @stack('styles')

    <!-- Document Title ===================== -->
    <title>@yield('front-title') | {{ getAppName() }}</title>
    {{-- data-turbo-eval="false": prevent Turbo Drive from re-executing head bundles on visit
         (avoids turbo-frame redefine, FullCalendar/Stripe double-load). --}}
    <script data-turbo-eval="false">
        // Soft-guard: if a bundle is still re-injected, ignore duplicate custom element defines.
        (function () {
            if (window.__mydentCustomElementGuard) return;
            window.__mydentCustomElementGuard = true;
            var define = CustomElementRegistry.prototype.define;
            CustomElementRegistry.prototype.define = function (name, constructor, options) {
                if (this.get(name)) {
                    return;
                }
                return define.call(this, name, constructor, options);
            };
        })();
    </script>
    <script src="{{ asset('messages.js') }}" data-turbo-eval="false"></script>
    <script src="{{ asset('assets/front/vendor/bootstrap.bundle.min.js') }}" data-turbo-eval="false"></script>
    <script src="{{ $mdFrontThirdPartyJs }}" data-turbo-eval="false" data-turbo-track="reload"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" data-turbo-eval="false"></script>
    <script src="{{ $mdFrontPagesJs }}" data-turbo-eval="false" data-turbo-track="reload"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}" data-turbo-eval="false"></script>


    <!-- JavaScript Bundle with Popper -->
    <script data-turbo-eval="false">
        let currencyIcon = '{{ getCurrencyIcon() }}'
        let isSetFirstFocus = false
        let csrfToken = "{{ csrf_token() }}"
        let defaultCountryCodeValue = "{{ getSettingValue('default_country_code') }}"
    </script>
    <script src="https://js.stripe.com/v3/" data-turbo-eval="false"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js" data-turbo-eval="false"></script>
    @if(!empty(config('app.google_recaptcha_site_key')))
        <script src="https://www.google.com/recaptcha/api.js" async defer data-turbo-eval="false"></script>
    @endif
    @routes

    <script data-turbo-eval="false">
        let appointmentStripePaymentUrl = '{{ url('appointment-stripe-charge') }}';
        let stripe = '';
        @if (config('services.stripe.key'))
            if (typeof Stripe !== 'undefined' && !window.__mydentStripe) {
                window.__mydentStripe = Stripe('{{ config('services.stripe.key') }}');
            }
            stripe = window.__mydentStripe || '';
        @endif
        let manually = "{{ \App\Models\Appointment::MANUALLY }}";
        let paystack = "{{ \App\Models\Appointment::PAYSTACK }}";
        let paypal = "{{ \App\Models\Appointment::PAYPAL }}";
        let stripeMethod = "{{ \App\Models\Appointment::STRIPE }}";
        let razorpayMethod = "{{ \App\Models\Appointment::RAZORPAY }}";
        let authorizeMethod = "{{ \App\Models\Appointment::AUTHORIZE }}";
        let paytmMethod = "{{ \App\Models\Appointment::PAYTM }}";
        let checkLanguageSession = '{{ checkLanguageSession() }}'
            Lang.setLocale(checkLanguageSession);
        let options = {
            'key': "{{ config('payments.razorpay.key') }}",
            'amount': 0, //  100 refers to 1
            'currency': 'INR',
            'name': "{{ getAppName() }}",
            'order_id': '',
            'description': '',
            'image': '{{ asset(getAppLogo()) }}', // logo here
            'callback_url': "{{ route('razorpay.success') }}",
            'prefill': {
                'email': '', // recipient email here
                'name': '', // recipient name here
                'contact': '', // recipient phone here
                'appointmentID': '', // appointmentID here
            },
            'readonly': {
                'name': 'true',
                'email': 'true',
                'contact': 'true',
            },
            'theme': {
                'color': '#E83D45',
            },
            'modal': {
                'ondismiss': function() {
                    $('.book-appointment-message').css('display', 'block');
                    let response =
                        '<div class="gen alert alert-danger">Appointment created successfully and payment not completed.</div>';
                    $('.book-appointment-message').html(response).delay(5000).hide('slow');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
            },
        }
    </script>
</head>

<body class="d-flex flex-column min-vh-100">
    <div id="mdPageLoader" class="md-page-loader" role="status" aria-live="polite" aria-label="Loading MyDent">
        <div class="md-page-loader__inner">
            <img src="{{ asset('assets/image/mydent-logo-transparent.png') }}"
                 alt="MyDent"
                 class="md-page-loader__logo"
                 width="180"
                 height="60">
            <div class="md-page-loader__bar" aria-hidden="true"><span></span></div>
            <p class="md-page-loader__text mb-0">Preparing your smile experience…</p>
        </div>
    </div>

    @include('fronts.layouts.header')
    @include('fronts.layouts.mobile-nav')
    <main class="flex-grow-1">
        @yield('front-content')
    </main>
    @include('fronts.layouts.footer')
    @stack('scripts')
    <script>
        (function () {
            var root = document.documentElement;
            var loader = document.getElementById('mdPageLoader');
            var done = false;
            var hideLoader = function () {
                if (done) return;
                done = true;
                root.classList.remove('md-page-loading');
                root.classList.add('md-page-ready');
                document.body.classList.add('md-page-ready');
                // Never leave content hidden
                document.querySelectorAll('.md-reveal').forEach(function (el) {
                    el.classList.add('is-visible');
                });
                if (!loader) return;
                loader.classList.add('is-done');
                setTimeout(function () {
                    if (loader && loader.parentNode) loader.parentNode.removeChild(loader);
                }, 280);
            };

            // Hide as soon as DOM is ready — don't wait for videos/images (window.load).
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function () {
                    setTimeout(hideLoader, 180);
                });
            } else {
                setTimeout(hideLoader, 180);
            }
            // Hard cap so a slow asset never blocks the UI
            setTimeout(hideLoader, 900);
            window.addEventListener('load', hideLoader);
        })();

        // Hard stop for broken image fallback loops (e.g. missing mydent-logo.png)
        document.addEventListener('error', function (event) {
            var el = event.target;
            if (!el || el.tagName !== 'IMG' || el.dataset.mdFallbackApplied === '1') {
                return;
            }
            el.dataset.mdFallbackApplied = '1';
            el.onerror = null;
            el.src = @json(asset('assets/image/mydent-logo-transparent.png'));
        }, true);

        document.addEventListener('DOMContentLoaded', function () {
            var header = document.getElementById('mdHeader');
            if (header) {
                var onScroll = function () {
                    header.classList.toggle('is-scrolled', window.scrollY > 8);
                };
                onScroll();
                window.addEventListener('scroll', onScroll, { passive: true });
            }

            // Mark all sections visible immediately (CSS also forces opacity:1).
            document.querySelectorAll('.md-reveal').forEach(function (el) {
                el.classList.add('is-visible');
            });
        });
    </script>
</body>


</html>
