@extends('fronts.layouts.app')
@section('front-title')
    Shopping Cart
@endsection
@section('front-content')
    <div class="md-commerce">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="md-section__title">Shopping cart</h1>
                <p class="md-section__lead">Review your selected dental products and clear aligner accessories.</p>
            </div>

            @include('flash::message')

            @if(count($cart) > 0)
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body p-4 p-md-5">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr class="text-muted text-uppercase small border-bottom">
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th style="min-width: 130px;">Quantity</th>
                                                <th>Subtotal</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $subtotal = 0; @endphp
                                            @foreach($cart as $id => $item)
                                                @php
                                                    $itemSubtotal = $item['price'] * $item['quantity'];
                                                    $subtotal += $itemSubtotal;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if(!empty($item['image']))
                                                                <img src="{{ asset($item['image']) }}" width="65" height="65" class="rounded me-3" style="object-fit:cover;" alt="{{ $item['name'] }}" onerror="this.src='{{ asset('assets/image/mydent-logo.png') }}'">
                                                            @else
                                                                <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                                                                    <i class="fa-solid fa-box text-muted"></i>
                                                                </div>
                                                            @endif
                                                            <h5 class="mb-0 fs-6 fw-bold">{{ $item['name'] }}</h5>
                                                        </div>
                                                    </td>
                                                    <td class="fw-bold">₹{{ number_format($item['price'], 2) }}</td>
                                                    <td>
                                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex align-items-center">
                                                            @csrf
                                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" class="form-control form-control-sm text-center me-2" style="width: 70px; height: auto;" onchange="this.form.submit()">
                                                        </form>
                                                    </td>
                                                    <td class="fw-bold text-primary">₹{{ number_format($itemSubtotal, 2) }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-outline-danger" title="Remove item">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                    <x-front.button href="{{ route('products.index') }}" variant="outline" size="sm">
                                        Continue shopping
                                    </x-front.button>
                                    <a href="{{ route('cart.clear') }}" class="btn btn-sm btn-link text-danger text-decoration-none">Clear cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body p-4 p-md-5">
                                <h4 class="fw-bold mb-4" style="font-family: var(--md-font-display);">Order summary</h4>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Subtotal</span>
                                    <span class="fw-bold">₹{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Estimated tax (5%)</span>
                                    <span>₹{{ number_format($subtotal * 0.05, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4 fs-4 fw-bold">
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
