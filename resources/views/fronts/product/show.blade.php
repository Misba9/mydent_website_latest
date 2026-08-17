@extends('fronts.layouts.app')

@section('front-title')
    {{ $product->name }} | {{ getAppName() }}
@endsection

@section('front-content')
<div class="product-detail-page py-6 bg-light" style="margin-top: 80px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Products</a></li>
                <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        @include('flash::message')

        <div class="card shadow-sm border-0 rounded-15 overflow-hidden">
            <div class="card-body p-4 p-md-8">
                <div class="row g-5 align-items-center">
                    <!-- Product Image -->
                    <div class="col-lg-6">
                        <div class="product-main-image bg-secondary rounded-15 overflow-hidden text-center p-4">
                            @if(!empty($product->thumbnail))
                                <img src="{{ asset($product->thumbnail) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid rounded-15" 
                                     style="max-height: 400px; object-fit: contain;"
                                     onerror="this.src='{{ asset('assets/image/infycare-logo.png') }}'">
                            @else
                                <div class="py-10">
                                    <i class="fa-solid fa-box-open text-muted" style="font-size: 80px;"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="col-lg-6">
                        <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-bold mb-3">
                            {{ $product->category ?? 'Dental Care' }}
                        </span>
                        
                        <h1 class="fw-bold text-gray-900 mb-3">{{ $product->name }}</h1>

                        <div class="d-flex align-items-center mb-4">
                            @php
                                $discountPrice = $product->discount_price;
                                $originalPrice = $product->price;
                                $hasDiscount = $discountPrice && $discountPrice < $originalPrice;
                            @endphp

                            @if($hasDiscount)
                                <h2 class="text-danger fw-bold mb-0 me-3">₹{{ number_format($discountPrice, 2) }}</h2>
                                <span class="text-muted text-decoration-line-through fs-5 me-3">₹{{ number_format($originalPrice, 2) }}</span>
                                <span class="badge bg-danger text-white rounded-pill">Save {{ round((($originalPrice - $discountPrice) / $originalPrice) * 100) }}%</span>
                            @else
                                <h2 class="text-primary fw-bold mb-0">₹{{ number_format($originalPrice, 2) }}</h2>
                            @endif
                        </div>

                        <p class="text-gray-700 fs-6 lh-lg mb-6">
                            {!! nl2br(e($product->description ?? 'Premium quality dental care accessory designed for MyDent aligners and orthodontic oral hygiene.')) !!}
                        </p>

                        <div class="mb-6">
                            <span class="badge bg-{{ ($product->quantity ?? 1) > 0 ? 'success' : 'danger' }} px-3 py-2">
                                {{ ($product->quantity ?? 1) > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-3">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-6 py-3 fw-bold shadow-sm">
                                    <i class="fa-solid fa-cart-shopping me-2"></i> Add to Cart
                                </button>
                            </form>

                            <form action="{{ route('checkout.buyNow', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-lg rounded-pill px-6 py-3 fw-bold">
                                    Buy Now
                                </button>
                            </form>
                        </div>

                        <div class="mt-6 pt-6 border-top">
                            <a href="{{ route('products.index') }}" class="text-decoration-none text-muted fw-semibold">
                                <i class="fa-solid fa-arrow-left me-2"></i> Back to Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
