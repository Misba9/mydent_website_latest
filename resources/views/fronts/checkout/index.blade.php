@extends('fronts.layouts.app')
@section('title')
    Checkout
@endsection
@section('content')
    <div class="checkout-page py-10 bg-light">
        <div class="container">
            <div class="text-center mb-8">
                <h1 class="text-primary fw-bold mb-2">Checkout</h1>
                <p class="text-gray-600">Complete your shipping and payment details.</p>
            </div>

            @include('layouts.errors')
            <div id="checkout-message-container" class="mb-4"></div>

            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="row g-6">
                    <div class="col-lg-7">
                        <div class="card shadow-sm border-0 rounded-15 mb-6">
                            <div class="card-body p-6">
                                <h4 class="card-title text-gray-900 fw-bold mb-5"><i class="fa-solid fa-truck me-2 text-primary"></i> Shipping Details</h4>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="John Doe">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Email Address</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="john@example.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="10-digit phone number">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Pincode</label>
                                        <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" required placeholder="ZIP / Pincode">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label required">Address</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address') }}" required placeholder="House/Street/Area">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">City</label>
                                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" required placeholder="City">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">State</label>
                                        <input type="text" name="state" class="form-control" value="{{ old('state') }}" required placeholder="State">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label required">Country</label>
                                        <input type="text" name="country" class="form-control" value="{{ old('country', 'India') }}" required placeholder="Country">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 rounded-15">
                            <div class="card-body p-6">
                                <h4 class="card-title text-gray-900 fw-bold mb-4"><i class="fa-solid fa-credit-card me-2 text-primary"></i> Payment Method</h4>
                                <div class="form-check mb-3 p-3 border rounded">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="payment_cod" value="COD" checked>
                                    <label class="form-check-label fw-bold text-gray-800" for="payment_cod">
                                        Cash on Delivery (COD)
                                    </label>
                                </div>
                                <div class="form-check p-3 border rounded">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="payment_online" value="Online">
                                    <label class="form-check-label fw-bold text-gray-800" for="payment_online">
                                        Online Payment (Razorpay / Cards / UPI)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card shadow-sm border-0 rounded-15 sticky-top" style="top: 20px;">
                            <div class="card-body p-6">
                                <h4 class="card-title text-gray-900 fw-bold mb-4">Your Order</h4>
                                @php $subtotal = 0; @endphp
                                <ul class="list-group list-group-flush mb-4">
                                    @foreach($cart as $item)
                                        @php 
                                            $itemTotal = $item['price'] * $item['quantity'];
                                            $subtotal += $itemTotal;
                                        @endphp
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <span class="fw-bold text-gray-800">{{ $item['name'] }}</span>
                                                <small class="text-muted d-block">Qty: {{ $item['quantity'] }}</small>
                                            </div>
                                            <span class="fw-bold text-gray-900">{{ getCurrencyIcon() }}{{ number_format($itemTotal, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="d-flex justify-content-between mb-2 text-gray-700">
                                    <span>Subtotal</span>
                                    <span>{{ getCurrencyIcon() }}{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-gray-700">
                                    <span>Tax (5%)</span>
                                    <span>{{ getCurrencyIcon() }}{{ number_format($subtotal * 0.05, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-6 fs-4 fw-bold text-gray-900">
                                    <span>Total Payable</span>
                                    <span class="text-primary">{{ getCurrencyIcon() }}{{ number_format(isset($total) ? $total : ($subtotal * 1.05), 2) }}</span>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold fs-5">
                                    Place Order Now <i class="fa-solid fa-check-circle ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const checkoutForm = $('#checkout-form');
        const submitBtn = checkoutForm.find('button[type="submit"]');
        const messageContainer = $('#checkout-message-container');

        function showMessage(msg, type = 'danger') {
            messageContainer.html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            $('html, body').animate({
                scrollTop: $(".checkout-page").offset().top - 50
            }, 200);
        }

        checkoutForm.on('submit', function (e) {
            const paymentMethod = $('input[name="payment_method"]:checked').val();

            if (paymentMethod === 'COD') {
                return true;
            }

            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const email = $('input[name="email"]').val().trim();
            const phone = $('input[name="phone"]').val().trim();
            const address = $('input[name="address"]').val().trim();
            const country = $('input[name="country"]').val().trim();
            const state = $('input[name="state"]').val().trim();
            const city = $('input[name="city"]').val().trim();
            const pincode = $('input[name="pincode"]').val().trim();

            if (!name || !email || !phone || !address || !country || !state || !city || !pincode) {
                showMessage('Please fill in all the required shipping fields.');
                return false;
            }

            if (!/^\d{10}$/.test(phone)) {
                showMessage('Please enter a valid 10-digit phone number.');
                return false;
            }

            submitBtn.prop('disabled', true).html('Processing Payment... <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');
            messageContainer.empty();

            $.ajax({
                url: "{{ route('checkout.razorpay.order') }}",
                type: "POST",
                data: checkoutForm.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    const options = {
                        key: response.key,
                        amount: response.amount,
                        currency: response.currency,
                        name: "MyDent",
                        description: "Teeth Aligners & Products Purchase",
                        order_id: response.razorpay_order_id,
                        prefill: {
                            name: response.name,
                            email: response.email,
                            contact: response.phone
                        },
                        handler: function (paymentResult) {
                            submitBtn.html('Verifying Payment... <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');

                            $.ajax({
                                url: "{{ route('checkout.razorpay.verify') }}",
                                type: "POST",
                                data: {
                                    razorpay_payment_id: paymentResult.razorpay_payment_id,
                                    razorpay_order_id: paymentResult.razorpay_order_id,
                                    razorpay_signature: paymentResult.razorpay_signature
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (verifyResponse) {
                                    if (verifyResponse.success) {
                                        window.location.href = verifyResponse.redirect;
                                    } else {
                                        showMessage(verifyResponse.error || 'Payment verification failed.');
                                        submitBtn.prop('disabled', false).html('Place Order Now <i class="fa-solid fa-check-circle ms-2"></i>');
                                    }
                                },
                                error: function (xhr) {
                                    const errMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Payment verification failed.';
                                    showMessage(errMsg);
                                    submitBtn.prop('disabled', false).html('Place Order Now <i class="fa-solid fa-check-circle ms-2"></i>');
                                }
                            });
                        },
                        modal: {
                            ondismiss: function () {
                                showMessage('Payment was cancelled. You can try again.');
                                submitBtn.prop('disabled', false).html('Place Order Now <i class="fa-solid fa-check-circle ms-2"></i>');
                            }
                        },
                        theme: {
                            color: "#4FB281"
                        }
                    };

                    const rzp = new Razorpay(options);
                    rzp.open();
                },
                error: function (xhr) {
                    const errMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Could not initiate online payment.';
                    showMessage(errMsg);
                    submitBtn.prop('disabled', false).html('Place Order Now <i class="fa-solid fa-check-circle ms-2"></i>');
                }
            });
        });
    });
</script>
@endpush
