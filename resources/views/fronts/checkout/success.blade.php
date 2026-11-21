@extends('fronts.layouts.app')
@section('front-title')
    Order placed successfully
@endsection
@section('front-content')
    <div class="md-commerce">
        <div class="container text-center">
            <div class="card mx-auto p-5" style="max-width: 600px;">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="fa-solid fa-circle-check text-success" style="font-size: 72px;"></i>
                    </div>
                    <h1 class="md-section__title mb-3">Thank you for your order</h1>
                    <p class="text-muted mb-4">Your order has been received and is being processed by our team.</p>

                    @if(session('success'))
                        <x-front.alert type="success" class="mb-4">{{ session('success') }}</x-front.alert>
                    @endif

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <x-front.button href="{{ route('medical') }}" variant="outline">Return home</x-front.button>
                        <x-front.button href="{{ route('products.index') }}">Continue shopping</x-front.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
