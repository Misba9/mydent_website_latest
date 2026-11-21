<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="{{ getAppName() }}" />
    <link rel="icon" href="{{ asset(getAppFavicon()) }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

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

    <!-- Google Fonts — Syne (display) + DM Sans (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ mix('css/front-third-party.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/front-pages.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/front/style.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/css/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/intlTelInput.css') }}">
    @stack('styles')

    <!-- Document Title ===================== -->
    <title>@yield('front-title') | {{ getAppName() }}</title>
    <script src="{{ asset('messages.js') }}"></script>
    <script src="{{ asset('assets/front/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ mix('js/front-third-party.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="{{ mix('js/front-pages.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>


    <!-- JavaScript Bundle with Popper -->
    <script data-turbo-eval="false">
        let currencyIcon = '{{ getCurrencyIcon() }}'
        let isSetFirstFocus = false
        let csrfToken = "{{ csrf_token() }}"
        let defaultCountryCodeValue = "{{ getSettingValue('default_country_code') }}"
    </script>
    <script src="//js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    @routes

    <script data-turbo-eval="false">
        let appointmentStripePaymentUrl = '{{ url('appointment-stripe-charge') }}';
        let stripe = '';
        @if (config('services.stripe.key'))
            stripe = Stripe('{{ config('services.stripe.key') }}');
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
                'color': '#1B5E4A',
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
    @include('fronts.layouts.header')
    <main class="flex-grow-1">
        @yield('front-content')
    </main>
    @include('fronts.layouts.footer')
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var nodes = document.querySelectorAll('.md-reveal');
            if (!('IntersectionObserver' in window) || !nodes.length) {
                nodes.forEach(function (el) { el.classList.add('is-visible'); });
                return;
            }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            nodes.forEach(function (el) { io.observe(el); });
        });
    </script>
</body>


</html>
