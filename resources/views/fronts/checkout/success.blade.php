@extends('fronts.layouts.app')
@section('title')
    Order Placed Successfully
@endsection
@section('content')
    <div class="order-success-page py-15 bg-light">
        <div class="container text-center">
            <div class="card shadow-sm border-0 rounded-15 mx-auto p-8" style="max-width: 600px;">
                <div class="card-body">
                    <div class="mb-5">
                        <i class="fa-solid fa-circle-check text-success" style="font-size: 80px;"></i>
                    </div>
                    <h1 class="text-gray-900 fw-bold mb-3">Thank You for Your Order!</h1>
                    <p class="text-gray-600 fs-5 mb-6">Your order has been received and is now being processed by our team.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-center gap-4">
                        <a href="{{ route('medical') }}" class="btn btn-outline-primary rounded-pill px-6">
                            Return to Home
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-6">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
