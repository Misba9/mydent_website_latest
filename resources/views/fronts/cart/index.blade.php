@extends('fronts.layouts.app')
@section('front-title')
    Shopping Cart
@endsection
@section('front-content')
    <div class="md-commerce md-cart-page">
        <div class="container pb-4">
            <div class="text-center mb-4 mb-lg-5">
                <h1 class="md-section__title">Shopping cart</h1>
                <p class="md-section__lead mb-0">Review your selected dental products and clear aligner accessories.</p>
            </div>

            @include('flash::message')

            @if(count($cart) > 0)
                <div class="row g-4 align-items-start">
                    <div class="col-lg-8">
                        <div class="card md-cart-card">
                            <div class="card-body p-3 p-md-4">
                                @php $subtotal = 0; @endphp
                                <ul class="md-cart-list list-unstyled mb-0">
                                    @foreach($cart as $id => $item)
                                        @php
                                            $itemSubtotal = $item['price'] * $item['quantity'];
                                            $subtotal += $itemSubtotal;
                                        @endphp
                                        <li class="md-cart-item">
                                            <div class="md-cart-item__media">
                                                @if(!empty($item['image']))
                                                    <img src="{{ asset($item['image']) }}"
                                                         width="72"
                                                         height="72"
                                                         alt="{{ $item['name'] }}"
                                                         onerror="{!! imgOnErrorFallback() !!}">
                                                @else
                                                    <div class="md-cart-item__placeholder">
                                                        <i class="fa-solid fa-box" aria-hidden="true"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="md-cart-item__body">
                                                <div class="md-cart-item__top">
                                                    <h2 class="md-cart-item__title">{{ $item['name'] }}</h2>
                                                    <a href="{{ route('cart.remove', $id) }}" class="md-cart-item__remove" title="Remove item" aria-label="Remove {{ $item['name'] }}">
                                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="md-cart-item__meta">
                                                    <span class="md-cart-item__price">₹{{ number_format($item['price'], 2) }}</span>
                                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="md-qty" data-md-qty data-turbo="false">
                                                        @csrf
                                                        <button type="button" class="md-qty__btn" data-md-qty-dec aria-label="Decrease quantity">−</button>
                                                        <input type="number"
                                                               name="quantity"
                                                               value="{{ (int) $item['quantity'] }}"
                                                               min="1"
                                                               max="99"
                                                               class="md-qty__input"
                                                               inputmode="numeric"
                                                               aria-label="Quantity"
                                                               data-md-qty-input>
                                                        <button type="button" class="md-qty__btn" data-md-qty-inc aria-label="Increase quantity">+</button>
                                                    </form>
                                                    <span class="md-cart-item__subtotal">₹{{ number_format($itemSubtotal, 2) }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="md-cart-actions">
                                    <x-front.button href="{{ route('products.index') }}" variant="outline" size="sm">
                                        Continue shopping
                                    </x-front.button>
                                    <a href="{{ route('cart.clear') }}" class="btn btn-sm btn-link text-danger text-decoration-none">Clear cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card md-cart-card">
                            <div class="card-body p-4">
                                <h2 class="h5 fw-bold mb-3" style="font-family: var(--md-font-display);">Order summary</h2>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span class="fw-bold">₹{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-muted">
                                    <span>Estimated tax (5%)</span>
                                    <span>₹{{ number_format($subtotal * 0.05, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4 fs-5 fw-bold">
                                    <span>Total</span>
                                    <span class="text-primary">₹{{ number_format($subtotal * 1.05, 2) }}</span>
                                </div>
                                <x-front.button href="{{ route('checkout.index') }}" class="w-100">
                                    Proceed to checkout
                                </x-front.button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <x-front.empty-state title="Your cart is empty" message="Explore mydent aligner care products to get started.">
                    <div class="mt-4">
                        <x-front.button href="{{ route('products.index') }}">Browse products</x-front.button>
                    </div>
                </x-front.empty-state>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
.md-cart-card {
    border: 1px solid var(--md-border, #E7EEF0) !important;
    border-radius: var(--md-radius-lg, 1.25rem) !important;
    box-shadow: none !important;
}
.md-cart-list { display: flex; flex-direction: column; gap: 0; }
.md-cart-item {
    display: flex;
    gap: 0.9rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--md-border, #E7EEF0);
}
.md-cart-item:last-of-type { border-bottom: 0; }
.md-cart-item__media img,
.md-cart-item__placeholder {
    width: 72px;
    height: 72px;
    border-radius: 0.75rem;
    object-fit: contain;
    background: #fff;
    border: 1px solid var(--md-border, #E7EEF0);
    display: block;
    flex-shrink: 0;
}
.md-cart-item__placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9aa8b0;
    background: #F5F9FA;
}
.md-cart-item__body { flex: 1 1 auto; min-width: 0; }
.md-cart-item__top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}
.md-cart-item__title {
    font-size: 0.98rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.35;
    color: var(--md-ink, #182B35);
}
.md-cart-item__remove {
    width: 36px;
    height: 36px;
    border-radius: 999px;
    border: 1.5px solid rgba(232, 61, 69, 0.35);
    color: var(--md-coral, #E83D45);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    text-decoration: none !important;
}
.md-cart-item__meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.65rem 0.85rem;
}
.md-cart-item__price {
    font-weight: 600;
    color: var(--md-ink, #182B35);
}
.md-cart-item__subtotal {
    font-weight: 800;
    color: var(--md-coral, #E83D45);
    margin-left: auto;
}
.md-cart-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
    padding-top: 1rem;
    margin-top: 0.5rem;
    border-top: 1px solid var(--md-border, #E7EEF0);
}
.md-qty {
    display: inline-flex;
    align-items: stretch;
    height: 38px;
    border: 1.5px solid var(--md-border, #E7EEF0);
    border-radius: 999px;
    overflow: hidden;
    background: #fff;
    width: max-content;
}
.md-qty__btn {
    appearance: none;
    border: 0;
    background: #F5F9FA;
    color: var(--md-ink, #182B35);
    width: 34px;
    min-width: 34px;
    height: 100%;
    padding: 0;
    margin: 0;
    font-size: 1.05rem;
    font-weight: 600;
    line-height: 1;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.md-qty__btn:hover {
    background: rgba(232, 61, 69, 0.08);
    color: var(--md-coral, #E83D45);
}
.md-qty__input {
    appearance: textfield;
    -moz-appearance: textfield;
    border: 0 !important;
    border-left: 1px solid var(--md-border, #E7EEF0) !important;
    border-right: 1px solid var(--md-border, #E7EEF0) !important;
    border-radius: 0 !important;
    width: 44px !important;
    min-width: 44px !important;
    max-width: 52px !important;
    height: 100% !important;
    min-height: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
    text-align: center;
    font-weight: 700;
    font-size: 0.92rem;
    color: var(--md-ink, #182B35);
    background: #fff !important;
    box-shadow: none !important;
    line-height: 1;
}
.md-qty__input::-webkit-outer-spin-button,
.md-qty__input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.md-qty__input:focus {
    outline: none;
    box-shadow: none !important;
}
@media (max-width: 575.98px) {
    .md-cart-item__subtotal { width: 100%; margin-left: 0; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-md-qty]').forEach(function (form) {
        var input = form.querySelector('[data-md-qty-input]');
        var dec = form.querySelector('[data-md-qty-dec]');
        var inc = form.querySelector('[data-md-qty-inc]');
        if (!input) return;

        var clamp = function (n) {
            n = parseInt(n, 10);
            if (isNaN(n) || n < 1) n = 1;
            if (n > 99) n = 99;
            return n;
        };

        var submitQty = function () {
            input.value = clamp(input.value);
            form.submit();
        };

        if (dec) {
            dec.addEventListener('click', function () {
                input.value = clamp(parseInt(input.value, 10) - 1);
                submitQty();
            });
        }
        if (inc) {
            inc.addEventListener('click', function () {
                input.value = clamp(parseInt(input.value, 10) + 1);
                submitQty();
            });
        }

        input.addEventListener('change', submitQty);
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitQty();
            }
        });
    });
});
</script>
@endpush
