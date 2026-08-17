@extends('fronts.layouts.app')
@section('front-title')
    Shopping Cart | {{ getAppName() }}
@endsection
@section('front-content')
    <div class="cart-page py-8 bg-light" style="margin-top: 80px;">
        <div class="container">
            <div class="text-center mb-6">
                <h1 class="text-primary fw-bold mb-2">Shopping Cart</h1>
                <p class="text-gray-600">Review your selected dental products and clear aligner accessories.</p>
            </div>

            @include('flash::message')

            @if(count($cart) > 0)
                <div class="row g-5">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 rounded-15 overflow-hidden">
                            <div class="card-body p-4 p-md-6">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr class="text-gray-600 text-uppercase fs-small border-bottom">
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
                                                                <img src="{{ asset($item['image']) }}" width="65" height="65" class="rounded me-3 object-cover" alt="{{ $item['name'] }}" onerror="this.src='{{ asset('assets/image/mydent-logo.png') }}'">
                                                            @else
                                                                <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                                                                    <i class="fa-solid fa-box text-muted fs-3"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h5 class="text-gray-900 mb-1 fs-6 fw-bold">{{ $item['name'] }}</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="fw-bold">₹{{ number_format($item['price'], 2) }}</td>
                                                    <td>
                                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex align-items-center">
                                                            @csrf
                                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" class="form-control form-control-sm text-center me-2" style="width: 70px;" onchange="this.form.submit()">
                                                        </form>
                                                    </td>
                                                    <td class="fw-bold text-primary">₹{{ number_format($itemSubtotal, 2) }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-icon btn-outline-danger rounded-circle" title="Remove item">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="fa-solid fa-arrow-left me-2"></i> Continue Shopping
                                    </a>
                                    <a href="{{ route('cart.clear') }}" class="btn btn-sm btn-link text-danger text-decoration-none">
                                        Clear Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 rounded-15">
                            <div class="card-body p-6">
                                <h4 class="card-title text-gray-900 fw-bold mb-4">Order Summary</h4>
                                <div class="d-flex justify-content-between mb-3 text-gray-700">
                                    <span>Subtotal</span>
                                    <span class="fw-bold">₹{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 text-gray-700">
                                    <span>Estimated Tax (5%)</span>
                                    <span>₹{{ number_format($subtotal * 0.05, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-6 text-gray-900 fs-4 fw-bold">
                                    <span>Total</span>
                                    <span class="text-primary">₹{{ number_format($subtotal * 1.05, 2) }}</span>
                                </div>
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                                    Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 rounded-15 text-center py-10 px-4 bg-white">
                            <div class="card-body">
                                <div class="mb-4">
                                    <i class="fa-solid fa-cart-shopping text-muted" style="font-size: 72px;"></i>
                                </div>
                                <h3 class="text-gray-900 fw-bold mb-3">Your Cart is Empty</h3>
                                <p class="text-gray-600 mb-6">Explore our dental clear aligners and hygiene products to fill your cart.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-8 py-3 fw-bold">
                                    Browse Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
