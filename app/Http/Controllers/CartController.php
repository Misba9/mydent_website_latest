<?php

// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->discount_price ?? $product->price,
                "image" => $product->thumbnail
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function showCart()
    {
        $cart = session()->get('cart', []);
        return view('fronts.cart.index', compact('cart'));
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product removed from cart.');
    }

    // In CartController.php
public function index()
{
    $cart = session('cart', []);
    return view('fronts.cart.index', compact('cart'));
}

public function buyNow($id, Request $request)
{
    $product = Product::findOrFail($id);

    // Store product in session
    session()->put('buy_now', [
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => $product->discount_price ?? $product->price,
    ]);

    // ✅ Redirect to GET route (checkout page)
    return redirect()->route('checkout.buy.now');
}


public function buyNowCheckout()
{
    $data = session('buy_now');

    if (!$data) {
        return redirect('/')->with('error', 'No product selected for Buy Now.');
    }

    $product = Product::findOrFail($data['product_id']);

    // Prepare a fake cart format for compatibility
    $cart = [
        $product->id => [
            'name' => $product->name,
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'image' => $product->thumbnail
        ]
    ];
    return view('fronts.checkout.index', compact('cart'));
}

}

