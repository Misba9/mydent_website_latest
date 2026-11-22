@extends('fronts.layouts.app')
@section('front-title')
    Checkout
@endsection
@section('front-content')
    <div class="checkout-page md-commerce">
        <div class="container pb-5">
            <div class="text-center mb-4 mb-lg-5">
                <span class="md-section__eyebrow">Secure checkout</span>
                <h1 class="md-section__title">Checkout</h1>
                <p class="md-section__lead mx-auto mb-0">Complete your shipping and payment details.</p>
            </div>

            @include('layouts.errors')
            <div id="checkout-message-container" class="mb-4"></div>

            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" data-turbo="false">
                @csrf
                <div class="row g-4 align-items-start">
                    <div class="col-lg-7">
                        <div class="md-checkout-card card mb-4">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="md-checkout-card__title">
                                    <i class="fa-solid fa-truck" aria-hidden="true"></i>
                                    Shipping details
                                </h2>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="checkout-name">Full name <span class="text-danger">*</span></label>
                                        <input id="checkout-name" type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="John Doe" autocomplete="name">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="checkout-email">Email address <span class="text-danger">*</span></label>
                                        <input id="checkout-email" type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="john@example.com" autocomplete="email">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="checkout-phone">Phone number <span class="text-danger">*</span></label>
                                        <input id="checkout-phone" type="tel" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="10-digit phone number" autocomplete="tel" inputmode="numeric">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="checkout-pincode">Pincode <span class="text-danger">*</span></label>
                                        <input id="checkout-pincode" type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" required placeholder="ZIP / Pincode" autocomplete="postal-code">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="checkout-address">Address <span class="text-danger">*</span></label>
                                        <input id="checkout-address" type="text" name="address" class="form-control" value="{{ old('address') }}" required placeholder="House / Street / Area" autocomplete="street-address">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="checkout-city">City <span class="text-danger">*</span></label>
                                        <input id="checkout-city" type="text" name="city" class="form-control" value="{{ old('city') }}" required placeholder="City" autocomplete="address-level2">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="checkout-state">State <span class="text-danger">*</span></label>
                                        <input id="checkout-state" type="text" name="state" class="form-control" value="{{ old('state') }}" required placeholder="State" autocomplete="address-level1">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="checkout-country">Country <span class="text-danger">*</span></label>
                                        <input id="checkout-country" type="text" name="country" class="form-control" value="{{ old('country', 'India') }}" required placeholder="Country" autocomplete="country-name">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md-checkout-card card">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="md-checkout-card__title">
                                    <i class="fa-solid fa-credit-card" aria-hidden="true"></i>
                                    Payment method
                                </h2>
                                <div class="md-pay-option form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="COD" checked>
                                    <label class="form-check-label" for="payment_cod">
                                        <span class="md-pay-option__name">Cash on Delivery (COD)</span>
                                        <span class="md-pay-option__hint">Pay when your order arrives</span>
                                    </label>
                                </div>
                                <div class="md-pay-option form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_online" value="Online">
                                    <label class="form-check-label" for="payment_online">
                                        <span class="md-pay-option__name">Online payment</span>
                                        <span class="md-pay-option__hint">Razorpay · Cards · UPI</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="md-checkout-card md-checkout-summary card">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="md-checkout-card__title mb-3">Your order</h2>

                                @php $subtotal = 0; @endphp
                                <ul class="md-order-lines list-unstyled mb-4">
                                    @foreach($cart as $item)
                                        @php
                                            $itemTotal = $item['price'] * $item['quantity'];
                                            $subtotal += $itemTotal;
                                        @endphp
                                        <li class="md-order-line">
                                            <div class="md-order-line__meta">
                                                <span class="md-order-line__name">{{ $item['name'] }}</span>
                                                <span class="md-order-line__qty">Qty {{ (int) $item['quantity'] }}</span>
                                            </div>
                                            <span class="md-order-line__price">{{ getCurrencyIcon() }}{{ number_format($itemTotal, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="md-order-totals">
                                    <div class="md-order-totals__row">
                                        <span>Subtotal</span>
                                        <span>{{ getCurrencyIcon() }}{{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="md-order-totals__row">
                                        <span>Tax (5%)</span>
                                        <span>{{ getCurrencyIcon() }}{{ number_format($subtotal * 0.05, 2) }}</span>
                                    </div>
                                    <div class="md-order-totals__row md-order-totals__row--total">
                                        <span>Total payable</span>
                                        <span>{{ getCurrencyIcon() }}{{ number_format(isset($total) ? $total : ($subtotal * 1.05), 2) }}</span>
                                    </div>
                                </div>

                                <button type="submit" class="md-btn md-btn--primary md-btn--block mt-4">
                                    Place order now
                                </button>
                                <p class="text-muted small text-center mt-3 mb-0">
                                    You can review items again from your <a href="{{ route('cart.index') }}">cart</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
.md-checkout-card {
    border: 1px solid var(--md-border, #E7EEF0) !important;
    border-radius: var(--md-radius-lg, 1.25rem) !important;
    box-shadow: none !important;
    background: #fff;
}
.md-checkout-card__title {
    font-family: var(--md-font-display, inherit);
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--md-ink, #182B35);
    margin: 0 0 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.55rem;
}
.md-checkout-card__title i {
    color: var(--md-coral, #E83D45);
    font-size: 1rem;
}
.checkout-page .form-label {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--md-ink, #182B35);
    margin-bottom: 0.4rem;
}
.checkout-page .form-control {
    min-height: 46px;
    border-radius: 0.65rem;
    border: 1.5px solid var(--md-border, #E7EEF0);
    background: #fff !important;
    color: var(--md-ink, #182B35);
    box-shadow: none;
}
.checkout-page .form-control:focus {
    border-color: var(--md-coral, #E83D45);
    box-shadow: 0 0 0 0.2rem rgba(232, 61, 69, 0.15);
    background: #fff !important;
}
.md-pay-option {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.95rem 1rem;
    border: 1.5px solid var(--md-border, #E7EEF0);
    border-radius: 0.85rem;
    margin-bottom: 0.75rem;
    background: #fff;
}
.md-pay-option:last-child { margin-bottom: 0; }
.md-pay-option .form-check-input {
    margin-top: 0.3rem;
    flex-shrink: 0;
}
.md-pay-option .form-check-input:checked {
    background-color: var(--md-coral, #E83D45);
    border-color: var(--md-coral, #E83D45);
}
.md-pay-option .form-check-label {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    cursor: pointer;
}
.md-pay-option__name {
    font-weight: 700;
    color: var(--md-ink, #182B35);
}
.md-pay-option__hint {
    font-size: 0.85rem;
    color: var(--md-muted, #6B7C86);
}
.md-checkout-summary {
    position: sticky;
    top: calc(var(--md-header-h, 72px) + 1rem);
}
.md-order-line {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--md-border, #E7EEF0);
}
.md-order-line:last-child { border-bottom: 0; }
.md-order-line__name {
    display: block;
    font-weight: 700;
    color: var(--md-ink, #182B35);
    line-height: 1.35;
}
.md-order-line__qty {
    display: inline-block;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--md-muted, #6B7C86);
    background: #F5F9FA;
    border-radius: 999px;
    padding: 0.15rem 0.55rem;
}
.md-order-line__price {
    font-weight: 700;
    white-space: nowrap;
    color: var(--md-ink, #182B35);
}
.md-order-totals {
    border-top: 1px solid var(--md-border, #E7EEF0);
    padding-top: 1rem;
}
.md-order-totals__row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.55rem;
    color: var(--md-muted, #6B7C86);
    font-size: 0.95rem;
}
.md-order-totals__row--total {
    margin-top: 0.75rem;
    margin-bottom: 0;
    padding-top: 0.75rem;
    border-top: 1px dashed var(--md-border, #E7EEF0);
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--md-ink, #182B35);
}
.md-order-totals__row--total span:last-child {
    color: var(--md-coral, #E83D45);
}
@media (max-width: 991.98px) {
    .md-checkout-summary {
        position: static;
        top: auto;
    }
}
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        const checkoutForm = $('#checkout-form');
        const submitBtn = checkoutForm.find('button[type="submit"]');
        const messageContainer = $('#checkout-message-container');
        const defaultBtnHtml = 'Place order now';

        function showMessage(msg, type = 'danger') {
            messageContainer.html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            const top = $(".checkout-page").offset()?.top || 0;
            $('html, body').animate({ scrollTop: Math.max(0, top - 90) }, 200);
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

            submitBtn.prop('disabled', true).html('Processing payment…');
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
                            submitBtn.html('Verifying payment…');

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
                                        submitBtn.prop('disabled', false).html(defaultBtnHtml);
                                    }
                                },
                                error: function (xhr) {
                                    const errMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Payment verification failed.';
                                    showMessage(errMsg);
                                    submitBtn.prop('disabled', false).html(defaultBtnHtml);
                                }
                            });
                        },
                        modal: {
                            ondismiss: function () {
                                showMessage('Payment was cancelled. You can try again.');
                                submitBtn.prop('disabled', false).html(defaultBtnHtml);
                            }
                        },
                        theme: {
                            color: "#E83D45"
                        }
                    };

                    const rzp = new Razorpay(options);
                    rzp.open();
                },
                error: function (xhr) {
                    const errMsg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Could not initiate online payment.';
                    showMessage(errMsg);
                    submitBtn.prop('disabled', false).html(defaultBtnHtml);
                }
            });
        });
    });
</script>
@endpush
