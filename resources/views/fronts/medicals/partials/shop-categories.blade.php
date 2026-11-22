<section class="md-section md-section--white md-reveal" aria-labelledby="md-products-title">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="md-section__eyebrow">Shop</span>
                <h2 id="md-products-title" class="md-section__title mb-2">Dental products</h2>
                <p class="md-section__lead mb-0">Care essentials from the MyDent shop — powered by your live catalogue.</p>
            </div>
            <a href="{{ route('products.index') }}" class="md-btn md-btn--outline" data-turbo="false">View all products</a>
        </div>

        @if(isset($featuredProducts) && $featuredProducts->count())
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                    @php
                        $discountPrice = $product->discount_price;
                        $originalPrice = $product->price;
                        $hasDiscount = $discountPrice && $discountPrice < $originalPrice;
                    @endphp
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <article class="md-product-card">
                            <a href="{{ route('front.product.show', $product->id) }}" class="md-product-card__media text-decoration-none">
                                <img src="{{ asset($product->thumbnail) }}"
                                     alt="{{ $product->name }}"
                                     width="400"
                                     height="220"
                                     loading="lazy"
                                     onerror="{!! imgOnErrorFallback() !!}">
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
        @elseif(isset($categories) && $categories->count())
            <div class="md-scroll-row">
                @foreach($categories as $category)
                    <a href="{{ route('products.index') }}" class="md-product-card text-decoration-none" style="min-width: 220px; max-width: 220px;" data-turbo="false">
                        <div class="md-product-card__media">
                            <img src="{{ asset($category->category_thumbnail) }}"
                                 alt="{{ $category->category }}"
                                 width="220"
                                 height="150"
                                 loading="lazy"
                                 style="height: 150px;"
                                 onerror="{!! imgOnErrorFallback() !!}">
                        </div>
                        <div class="md-product-card__body">
                            <h3 class="md-product-card__title mb-0">{{ ucfirst($category->category) }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">Products will appear here when available in the catalogue.</p>
        @endif
    </div>
</section>
