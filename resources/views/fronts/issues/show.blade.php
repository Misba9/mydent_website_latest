@extends('fronts.layouts.app')
@section('front-title', $card['title'] . ' Treatment')
@section('meta_description', Str::limit(strip_tags($card['desc']), 150))
@section('front-content')
<section class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin-top:100px; padding: 80px 0 60px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 text-white font-weight-bold mb-3">{{ $card['title'] }}</h1>
                <p class="lead text-white opacity-90">Comprehensive information and expert guidance</p>
            </div>
        </div>
    </div>
</section>

<section class="main-content" style="background-color: #f8fafc; padding: 60px 0 80px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Featured Video / Image Card -->
                <div class="card border-0 shadow-lg mb-5" style="border-radius: 20px; overflow: hidden;">
                    <div class="position-relative">
                        @if(isset($card['video']))
                            <video autoplay muted loop playsinline class="w-100" style="height: 400px; object-fit: cover;">
                                <source src="{{ asset('storage/vids/' . $card['video']) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @elseif(isset($card['image']))
                            <img src="{{ $card['image'] }}" class="card-img-top" alt="{{ $card['title'] }}" 
                                 style="height: 400px; object-fit: cover; width: 100%;">
                        @else
                            <img src="{{ asset('assets/image/mydent-logo.png') }}" class="card-img-top" alt="{{ $card['title'] }}" 
                                 style="height: 400px; object-fit: contain; width: 100%; background-color: #f8fafc; padding: 40px;">
                        @endif
                        <div class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none" 
                             style="background: linear-gradient(to bottom, rgba(0,0,0,0.05), rgba(0,0,0,0.15)); z-index: 1;"></div>
                    </div>
                </div>

                <!-- Content Cards Grid -->
                <div class="row g-4 mb-5">
                    <!-- Description Card -->
                    <div class="col-lg-12">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-info-circle text-white fs-5"></i>
                                    </div>
                                    <h4 class="card-title mb-0 text-primary font-weight-bold">Description</h4>
                                </div>
                                <p class="card-text text-muted lh-lg">{{ $card['desc'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Details Card -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-clipboard-list text-white fs-5"></i>
                                    </div>
                                    <h4 class="card-title mb-0 text-danger font-weight-bold">Details</h4>
                                </div>
                                <p class="card-text text-muted lh-lg">{{ $card['details'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Causes Card -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe, #00f2fe); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-search text-white fs-5"></i>
                                    </div>
                                    <h4 class="card-title mb-0 text-info font-weight-bold">Causes</h4>
                                </div>
                                <p class="card-text text-muted lh-lg">{{ $card['causes'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Symptoms Card -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fa709a, #fee140); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-exclamation-triangle text-white fs-5"></i>
                                    </div>
                                    <h4 class="card-title mb-0 text-warning font-weight-bold">Symptoms</h4>
                                </div>
                                <p class="card-text text-muted lh-lg">{{ $card['symptoms'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Card -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-wrapper me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #a8edea, #fed6e3); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-medkit text-white fs-5"></i>
                                    </div>
                                    <h4 class="card-title mb-0 text-success font-weight-bold">Treatment</h4>
                                </div>
                                <p class="card-text text-muted lh-lg">{{ $card['treatment'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0" style="border-radius: 20px 20px 0 0; padding: 30px 30px 20px;">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-question-circle text-white fs-4"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 text-primary font-weight-bold">Frequently Asked Questions</h3>
                                <p class="text-muted mb-0">Get answers to common questions</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="accordion accordion-flush" id="faqAccordion">
                            @foreach($card['faqs'] as $index => $faq)
                                <div class="accordion-item border-0 mb-3" style="border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }} fw-semibold" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ $index }}" 
                                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                                                aria-controls="collapse{{ $index }}"
                                                style="background: {{ $index === 0 ? 'linear-gradient(135deg, #667eea, #764ba2)' : '#f8fafc' }}; 
                                                       color: {{ $index === 0 ? 'white' : '#4a5568' }}; 
                                                       border: none; 
                                                       padding: 20px 25px; 
                                                       font-size: 16px;
                                                       transition: all 0.3s ease;">
                                            <i class="fas fa-chevron-down me-3" style="transition: transform 0.3s ease;"></i>
                                            {{ $faq['q'] }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" 
                                         class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                                         aria-labelledby="heading{{ $index }}" 
                                         data-bs-parent="#faqAccordion">
                                        <div class="accordion-body" style="padding: 25px; background-color: white; color: #4a5568; line-height: 1.7;">
                                            {{ $faq['a'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                
            </div>
        </div>
    </div>
</section>

<style>
    .accordion-button:not(.collapsed)::after {
        transform: rotate(180deg);
    }
    
    .accordion-button::after {
        display: none;
    }
    
    .accordion-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .icon-wrapper {
        transition: transform 0.3s ease;
    }
    
    .card:hover .icon-wrapper {
        transform: scale(1.1);
    }
    
    .font-weight-bold {
        font-weight: 700 !important;
    }
    
    .lh-lg {
        line-height: 1.8 !important;
    }
</style>

<!-- Floating Action Buttons -->
<div class="floating-buttons">
    <!-- WhatsApp Button -->
    <a href="https://wa.me/1234567890" target="_blank" class="floating-btn whatsapp-btn" title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <!-- Call Button -->
    <a href="tel:+1234567890" class="floating-btn call-btn" title="Call Now">
        <i class="fas fa-phone"></i>
    </a>
    
    <!-- Book Appointment Button -->
    <a href="{{ route('medicalAppointment') }}" class="floating-btn book-btn" title="Book Appointment">
        <i class="fas fa-calendar-plus"></i>
    </a>
</div>

<style>
    .floating-buttons {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .floating-btn:hover {
        transform: scale(1.1);
        color: white;
        text-decoration: none;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
    }

    .floating-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .floating-btn:hover::before {
        left: 100%;
    }

    .whatsapp-btn {
        background: linear-gradient(135deg, #25D366, #128C7E);
        animation: pulse-whatsapp 2s infinite;
    }

    .call-btn {
        background: linear-gradient(135deg, #007bff, #0056b3);
        animation: pulse-call 2s infinite 0.5s;
    }

    .book-btn {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        animation: pulse-book 2s infinite 1s;
    }

    @keyframes pulse-whatsapp {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(37, 211, 102, 0.3); }
    }

    @keyframes pulse-call {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(0, 123, 255, 0.3); }
    }

    @keyframes pulse-book {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(40, 167, 69, 0.3); }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .floating-buttons {
            bottom: 20px;
            right: 20px;
            gap: 12px;
        }

        .floating-btn {
            width: 55px;
            height: 55px;
            font-size: 22px;
        }
    }

    /* Tooltip styles */
    .floating-btn[title]:hover::after {
        content: attr(title);
        position: absolute;
        right: 70px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 1;
        pointer-events: none;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Additional animations */
    .floating-btn:active {
        transform: scale(0.95);
    }

    /* Smooth entrance animation */
    .floating-buttons {
        animation: slideInRight 0.8s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Staggered animation for individual buttons */
    .whatsapp-btn {
        animation-delay: 0.2s;
        opacity: 0;
        animation: slideInRight 0.6s ease-out 0.2s forwards, pulse-whatsapp 2s infinite 2s;
    }

    .call-btn {
        animation-delay: 0.4s;
        opacity: 0;
        animation: slideInRight 0.6s ease-out 0.4s forwards, pulse-call 2s infinite 2.5s;
    }

    .book-btn {
        animation-delay: 0.6s;
        opacity: 0;
        animation: slideInRight 0.6s ease-out 0.6s forwards, pulse-book 2s infinite 3s;
    }
</style>

@endsection