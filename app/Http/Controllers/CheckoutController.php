<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checking out.');
        }

        // Calculate totals server-side
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $taxRate = 0.05;
        $tax     = round($subtotal * $taxRate, 2);
        $total   = round($subtotal + $tax, 2);

        $razorpayKey = config('payments.razorpay.key');

        return view('fronts.checkout.index', compact('cart', 'subtotal', 'tax', 'total', 'razorpayKey'));
    }

    /**
     * Create a Razorpay order on the server — called via AJAX when user chooses Online payment.
     * Returns the Razorpay order ID to the frontend.
     * The frontend NEVER knows the secret key.
     */
    public function createRazorpayOrder(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|digits:10',
            'email'   => 'required|email',
            'address' => 'required|string',
            'country' => 'required|string',
            'state'   => 'required|string',
            'city'    => 'required|string',
            'pincode' => 'required|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty.'], 422);
        }

        // Always calculate amount server-side — never trust frontend
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $total = round($subtotal * 1.05, 2);

        $keyId     = config('payments.razorpay.key');
        $keySecret = config('payments.razorpay.secret');

        if (empty($keyId) || empty($keySecret)) {
            Log::error('Razorpay credentials missing. Set RAZOR_KEY and RAZOR_SECRET in .env');
            return response()->json([
                'error' => 'Online payment is temporarily unavailable. Please use COD.'
            ], 503);
        }

        try {
            $api = new \Razorpay\Api\Api($keyId, $keySecret);

            $rzpOrder = $api->order->create([
                'amount'   => intval($total * 100), // amount in paise
                'currency' => 'INR',
                'receipt'  => 'mydent_' . uniqid(),
                'notes'    => [
                    'customer_name'  => $request->name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                ],
            ]);

            // Temporarily store order details in session for verification step
            session()->put('pending_order', [
                'razorpay_order_id' => $rzpOrder->id,
                'amount_paise'      => intval($total * 100),
                'name'              => $request->name,
                'phone'             => $request->phone,
                'email'             => $request->email,
                'address'           => $request->address,
                'city'              => $request->city,
                'state'             => $request->state,
                'country'           => $request->country,
                'pincode'           => $request->pincode,
            ]);

            return response()->json([
                'razorpay_order_id' => $rzpOrder->id,
                'amount'            => intval($total * 100),
                'currency'          => 'INR',
                'key'               => $keyId, // public key only — safe to expose
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Could not initiate payment. Please try again.'], 500);
        }
    }

    /**
     * Verify Razorpay payment signature on the server after payment completes.
     * This is called by the frontend after Razorpay checkout succeeds.
     * NEVER mark an order as paid before this verification.
     */
    public function verifyRazorpayPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $keySecret = config('payments.razorpay.secret');

        // Server-side HMAC signature verification
        $expectedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            $keySecret
        );

        if (!hash_equals($expectedSignature, $request->razorpay_signature)) {
            Log::warning('Razorpay signature mismatch', $request->all());
            return response()->json(['error' => 'Payment verification failed. Please contact support.'], 422);
        }

        // Retrieve the pending order from session
        $pendingOrder = session()->get('pending_order');

        if (!$pendingOrder || $pendingOrder['razorpay_order_id'] !== $request->razorpay_order_id) {
            Log::warning('Pending order session mismatch or missing');
            return response()->json(['error' => 'Session expired. Please place order again.'], 422);
        }

        $cart = session()->get('cart', []);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax   = round($subtotal * 0.05, 2);
        $total = round($subtotal + $tax, 2);

        $productNames = collect($cart)->pluck('name')->toArray();

        // Create the order in DB only after verified payment
        $order = Order::create([
            'name'                => $pendingOrder['name'],
            'email'               => $pendingOrder['email'],
            'phone'               => $pendingOrder['phone'],
            'address'             => $pendingOrder['address'],
            'city'                => $pendingOrder['city'],
            'state'               => $pendingOrder['state'],
            'country'             => $pendingOrder['country'],
            'pincode'             => $pendingOrder['pincode'],
            'payment_method'      => 'Online',
            'products'            => json_encode($productNames),
            'subtotal'            => $subtotal,
            'tax'                 => $tax,
            'total'               => $total,
            'status'              => 'Pending',
            'razorpay_order_id'   => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'payment_status'      => Order::PAYMENT_STATUS_PAID,
        ]);

        // Clear cart and pending order from session
        session()->forget(['cart', 'pending_order', 'buy_now']);

        Log::info('Order placed successfully via Razorpay', ['order_id' => $order->id]);

        return response()->json([
            'success'  => true,
            'message'  => 'Payment successful! Your order has been placed.',
            'order_id' => $order->id,
            'redirect' => route('checkout.success'),
        ]);
    }

    /**
     * Handle COD and form submission for non-Razorpay orders.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|digits:10',
            'email'          => 'required|email',
            'address'        => 'required|string',
            'country'        => 'required|string',
            'state'          => 'required|string',
            'city'           => 'required|string',
            'pincode'        => 'required|string',
            'payment_method' => 'required|in:COD',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Server-side total calculation
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax   = round($subtotal * 0.05, 2);
        $total = round($subtotal + $tax, 2);

        $productNames = collect($cart)->pluck('name')->toArray();

        Order::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'address'        => $request->address,
            'city'           => $request->city,
            'state'          => $request->state,
            'country'        => $request->country,
            'pincode'        => $request->pincode,
            'payment_method' => 'COD',
            'products'       => json_encode($productNames),
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'total'          => $total,
            'status'         => 'Pending',
            'payment_status' => Order::PAYMENT_STATUS_COD,
        ]);

        session()->forget(['cart', 'buy_now']);

        return redirect()->route('checkout.success')->with('success', 'Order placed successfully! We will contact you to confirm delivery.');
    }

    /**
     * Order/payment success page.
     */
    public function paymentSuccess()
    {
        return view('fronts.checkout.success');
    }
}
