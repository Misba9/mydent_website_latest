@extends('fronts.layouts.app')
@section('title')
    Shopping Cart
@endsection
@section('content')
    <div class="cart-page py-10 bg-light">
        <div class="container">
            <div class="text-center mb-8">
                <h1 class="text-primary fw-bold mb-2">Shopping Cart</h1>
                <p class="text-gray-600">Review your selected dental products before checkout.</p>
            </div>

            @include('flash::message')

            @if(count($cart) > 0)
                <div class="row g-6">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 rounded-15 overflow-hidden">
                            <div class="card-body p-6">
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr class="text-gray-600 text-uppercase fs-small">
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
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
                                                                <img src="{{ asset($item['image']) }}" width="60" class="rounded me-3" alt="{{ $item['name'] }}">
                                                            @else
                                                                <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                                    <i class="fa-solid fa-box text-muted"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h5 class="text-gray-900 mb-0 fs-6 fw-bold">{{ $item['name'] }}</h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="fw-bold">{{ getCurrencyIcon() }}{{ number_format($item['price'], 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-light text-dark fs-6 px-3 py-2 border">{{ $item['quantity'] }}</span>
                                                    </td>
                                                    <td class="fw-bold text-primary">{{ getCurrencyIcon() }}{{ number_format($itemSubtotal, 2) }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Remove item">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
                                    <span class="fw-bold">{{ getCurrencyIcon() }}{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 text-gray-700">
                                    <span>Estimated Tax (5%)</span>
                                    <span>{{ getCurrencyIcon() }}{{ number_format($subtotal * 0.05, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-6 text-gray-900 fs-4 fw-bold">
                                    <span>Total</span>
                                    <span class="text-primary">{{ getCurrencyIcon() }}{{ number_format($subtotal * 1.05, 2) }}</span>
                                </div>
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">
                                    Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 rounded-15 text-center py-10">
                    <div class="card-body">
                        <i class="fa-solid fa-cart-shopping fs-1 text-muted mb-4"></i>
                        <h3 class="text-gray-800 fw-bold mb-3">Your Cart is Empty</h3>
                        <p class="text-muted mb-6">Browse our shop to find products you need.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-6">
                            Browse Products
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
