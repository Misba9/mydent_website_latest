@extends('fronts.layouts.app')

@section('front-title')
    {{ __('messages.web.medical_ecom') }}
@endsection

@section('front-content')

<!-- Banner Section -->
@php
    $banners = \App\Models\MainBanner::where('page', 'products')->get();
@endphp

@if($banners->count())
<section class="main-banner mb-4" style="margin-top: 90px;">
    <div id="mainBannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($banners as $key => $banner)
                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="d-block w-100 banner-image">
                    
                    @if($banner->title)
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="banner-title">{{ $banner->title }}</h5>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#mainBannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#mainBannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
</section>
@endif

<div class="container-fluid px-4 py-5" style="margin-top: 70px;">
    <!-- Section Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-dark mb-3">Our Products</h1>
        <div class="section-underline mx-auto mb-4"></div>
        <p class="lead text-muted">Discover our premium collection of mydent aligner accessories</p>
    </div>

    <!-- Enhanced Category Filter -->
    <div class="filter-section mb-5">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <button class="filter-btn active" data-category="all">
                <i class="fas fa-th-large me-2"></i>
                <span>All Products</span>
                <div class="filter-btn-bg"></div>
            </button>
            @foreach($categories as $category)
                <button class="filter-btn" data-category="{{ $category }}">
                    <i class="fas fa-tag me-2"></i>
                    <span>{{ $category }}</span>
                    <div class="filter-btn-bg"></div>
                </button>
            @endforeach
        </div>
    </div>

    @if($products->isEmpty())
        <div class="empty-state text-center py-5">
            <div class="empty-icon mb-4">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="mb-3 text-muted">No Products Found</h3>
            <p class="text-muted">We're working on adding more products to our collection.</p>
        </div>
    @else
        <!-- Enhanced Product Grid -->
        <div class="products-grid mt-5">
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 product-card" data-category="{{ $product->category }}">
                        <div class="product-item">
                            <a href="{{ route('front.product.show', $product->id) }}" class="product-link">
                                <div class="product-image-container">
                                    <img src="{{ asset($product->thumbnail) }}" 
                                         class="product-image" 
                                         alt="{{ $product->name }}" 
                                         loading="lazy"
                                         onload="this.style.opacity='1'">
                                    <div class="product-overlay">
                                        <div class="overlay-content">
                                            <i class="fas fa-eye"></i>
                                            <span>View Details</span>
                                        </div>
                                    </div>
                                    @php
                                        $discountPrice = $product->discount_price;
                                        $originalPrice = $product->price;
                                        $hasDiscount = $discountPrice && $discountPrice < $originalPrice;
                                        $discountPercent = $hasDiscount ? round((($originalPrice - $discountPrice) / $originalPrice) * 100) : 0;
                                    @endphp
                                    @if($hasDiscount)
                                        <div class="discount-badge">
                                            -{{ $discountPercent }}%
                                        </div>
                                    @endif
                                </div>
                                <div class="product-content">
                                    <h5 class="product-title">{{ $product->name }}</h5>
                                    <div class="product-price">
                                        @if($hasDiscount)
                                            <span class="price-original">₹{{ number_format($originalPrice, 2) }}</span>
                                            <span class="price-discount">₹{{ number_format($discountPrice, 2) }}</span>
                                        @else
                                            <span class="price-current">₹{{ number_format($originalPrice, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
/* Base Styles */
.banner-image {
    width: 100%;
    height: 600px;
    object-fit: cover;
}

.banner-title {
    font-size: 2rem;
    background-color: rgba(0, 0, 0, 0.5);
    display: inline-block;
    padding: 10px 20px;
    border-radius: 8px;
}

.section-underline {
    width: 80px;
    height: 4px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-radius: 2px;
}

/* Filter Section */
.filter-btn {
    position: relative;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    padding: 12px 24px;
    font-weight: 600;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.filter-btn-bg {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #007bff, #0056b3);
    transition: left 0.3s ease;
    z-index: -1;
}

.filter-btn:hover .filter-btn-bg,
.filter-btn.active .filter-btn-bg {
    left: 0;
}

.filter-btn:hover,
.filter-btn.active {
    color: white;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,123,255,0.3);
}

/* Product Grid */
.product-card {
    display: block !important;
    opacity: 1 !important;
    transform: none !important;
    transition: all 0.3s ease;
}

.product-item {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
}

.product-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    height: 250px;
    background: #f5f5f5;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
    opacity: 0;
}

.product-item:hover .product-image {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-item:hover .product-overlay {
    opacity: 1;
}

.discount-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(45deg, #dc3545, #c82333);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
}

.product-content {
    padding: 20px;
    text-align: center;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 12px;
}

.price-original {
    color: #6c757d;
    text-decoration: line-through;
    font-size: 0.9rem;
}

.price-discount {
    color: #dc3545;
    font-weight: 700;
    font-size: 1.2rem;
}

/* Scroll Animation */
.product-card.animate-in {
    animation: fadeInUp 0.6s ease-out;
}
.product-link {
    text-decoration: none !important;
}

/* Specifically ensure product title and price have no underline */
.product-title, .product-price, .product-price span {
    text-decoration: none !important;
}

/* Remove any hover underlines */
.product-link:hover {
    text-decoration: none !important;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Styles */
@media (max-width: 768px) {
    .banner-image {
        height: 400px;
    }
    
    .product-image-container {
        height: 200px;
    }
}

@media (max-width: 576px) {
    .banner-image {
        height: 250px;
    }
    
    .filter-btn {
        padding: 8px 16px;
        font-size: 0.8rem;
    }
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize all products as visible
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.style.display = 'block';
        card.style.opacity = '1';
        card.style.transform = 'none';
    });

    // Filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const selectedCategory = this.getAttribute('data-category');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter products
            productCards.forEach(card => {
                const productCategory = card.getAttribute('data-category');
                
                if (selectedCategory === 'all' || productCategory === selectedCategory) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Intersection Observer for scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observe all product cards
    productCards.forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush