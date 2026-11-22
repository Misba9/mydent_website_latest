@extends('fronts.layouts.app')

@section('front-title')
    {{ __('messages.web.medical_ecom') }}
@endsection

@section('front-content')

@include('fronts.partials.banner-carousel', [
    'page' => 'products',
    'fallbackImage' => 'images/mydent-care.jpeg',
    'fallbackTitle' => 'Shop mydent',
])

<section class="md-section md-section--soft md-shop-page">
    <div class="container">
        <div class="text-center mb-4 mb-lg-5">
            <span class="md-section__eyebrow">Shop</span>
            <h1 class="md-section__title">Our products</h1>
            <p class="md-section__lead mx-auto mb-0">Discover our premium collection of MyDent aligner accessories</p>
        </div>

        <div class="md-shop-filters d-flex flex-wrap justify-content-center gap-2 mb-4 mb-lg-5" role="tablist" aria-label="Product categories">
            <button type="button" class="md-shop-filter is-active" data-category="all" aria-pressed="true">
                <i class="fas fa-th-large" aria-hidden="true"></i>
                <span>All Products</span>
            </button>
            @foreach($categories as $category)
                <button type="button" class="md-shop-filter" data-category="{{ strtolower(trim($category)) }}" aria-pressed="false">
                    <i class="fas fa-tag" aria-hidden="true"></i>
                    <span>{{ $category }}</span>
                </button>
            @endforeach
        </div>

        @if($products->isEmpty())
            <div class="text-center py-5">
                <h3 class="mb-2">No Products Found</h3>
                <p class="text-muted mb-0">We're working on adding more products to our collection.</p>
            </div>
        @else
            <div class="row g-4" id="mdShopGrid">
                @foreach($products as $product)
                    @php
                        $discountPrice = $product->discount_price;
                        $originalPrice = $product->price;
                        $hasDiscount = $discountPrice && $discountPrice < $originalPrice;
                        $discountPercent = $hasDiscount ? round((($originalPrice - $discountPrice) / $originalPrice) * 100) : 0;
                        $thumb = $product->thumbnail ?: 'assets/image/mydent-logo-transparent.png';
                        $catKey = strtolower(trim((string) $product->category));
                    @endphp
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 md-shop-item" data-category="{{ $catKey }}">
                        <article class="md-product-card">
                            <a href="{{ route('front.product.show', $product->id) }}" class="md-product-card__media text-decoration-none position-relative">
                                <img src="{{ asset($thumb) }}"
                                     alt="{{ $product->name }}"
                                     width="400"
                                     height="300"
                                     loading="lazy"
                                     style="object-fit: contain; padding: 1.25rem; background: #fff;"
                                     onerror="{!! imgOnErrorFallback('assets/image/mydent-logo-transparent.png') !!}">
                                @if($hasDiscount)
                                    <span class="md-shop-badge">-{{ $discountPercent }}%</span>
                                @endif
                            </a>
                            <div class="md-product-card__body">
                                <h3 class="md-product-card__title">{{ $product->name }}</h3>
                                <div class="md-product-card__price">
                                    @if($hasDiscount)
                                        <span class="md-price">₹{{ number_format($discountPrice, 2) }}</span>
                                        <span class="md-price-old">₹{{ number_format($originalPrice, 2) }}</span>
                                    @else
                                        <span class="md-price">₹{{ number_format($originalPrice, 2) }}</span>
                                    @endif
                                </div>
                                <div class="md-product-card__actions">
                                    <a href="{{ route('front.product.show', $product->id) }}" class="md-btn md-btn--outline md-btn--sm">View</a>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="md-product-card__cart-form">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="md-btn md-btn--primary md-btn--sm">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            <p id="mdShopEmpty" class="text-center text-muted mt-4 mb-0 d-none">No products in this category.</p>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.md-shop-filter');
    var items = document.querySelectorAll('.md-shop-item');
    var empty = document.getElementById('mdShopEmpty');
    if (!buttons.length || !items.length) return;

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var selected = (btn.getAttribute('data-category') || 'all').toLowerCase();

            buttons.forEach(function (b) {
                b.classList.toggle('is-active', b === btn);
                b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
            });

            var visible = 0;
            items.forEach(function (item) {
                var cat = (item.getAttribute('data-category') || '').toLowerCase();
                var show = selected === 'all' || cat === selected;
                item.classList.toggle('d-none', !show);
                if (show) visible += 1;
            });

            if (empty) {
                empty.classList.toggle('d-none', visible > 0);
            }
        });
    });
});
</script>
@endpush
