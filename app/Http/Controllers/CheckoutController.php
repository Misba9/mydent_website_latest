<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }

        // Calculate total here to pass to blade
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $taxRate = 0.05;
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;

        return view('fronts.checkout.index', compact('cart', 'total'));
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|digits:10',
        'email' => 'required|email',
        'address' => 'required|string',
        'country' => 'required|string',
        'state' => 'required|string',
        'city' => 'required|string',
        'pincode' => 'required|string',
        'payment_method' => 'required|in:COD,Online',
    ]);

    // Check for buy_now session first
    $cart = session()->get('cart', []);
    $buyNow = session()->get('buy_now');

    if ($buyNow) {
        $product = \App\Models\Product::find($buyNow['product_id']);
        $cart = [
            $product->id => [
                'name' => $product->name,
                'quantity' => $buyNow['quantity'],
                'price' => $buyNow['price'],
                'image' => $product->thumbnail
            ]
        ];
    }

    if (empty($cart)) {
        return back()->with('error', 'Cart is empty.');
    }

    // Calculate total again (server-side)
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $taxRate = 0.05;
    $tax = $subtotal * $taxRate;
    $total = $subtotal + $tax;

    // If payment method is online, capture it first
    if ($request->payment_method == 'Online') {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            // ✅ Manually capture the payment
            if ($payment->status === 'authorized') {
                $payment = $payment->capture([
                    'amount' => intval($total * 100), // amount in paise
                ]);
            }

            if ($payment->status === 'captured') {
                // Clear buy_now session after order
                if ($buyNow) session()->forget('buy_now');
                else session()->forget('cart');
                return $this->createOrderWithCart($request, $total, 'Paid', $cart);
            } else {
                return back()->with('error', 'Payment was not successful. Status: ' . $payment->status);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Payment verification error: ' . $e->getMessage());
        }
    }

    // COD fallback
    if ($buyNow) session()->forget('buy_now');
    else session()->forget('cart');
    return $this->createOrderWithCart($request, $total, 'Pending', $cart);
}


    private function createOrderWithCart(Request $request, $total, $paymentStatus, $cart)
{
    $productNames = collect($cart)->pluck('name')->toArray();

    Order::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'city' => $request->city,
        'state' => $request->state,
        'country' => $request->country,
        'pincode' => $request->pincode,
        'payment_method' => $request->payment_method,
        'products' => json_encode($productNames),
        'payment_status' => $paymentStatus,
        'total' => $total,
        'status' => 'Pending',
    ]);

    return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
}

    public function paymentSuccess()
    {
        return view('fronts.checkout.success');  // Create a success blade view
    }
}
