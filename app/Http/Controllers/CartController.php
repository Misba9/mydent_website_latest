<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('fronts.cart.index', compact('cart'));
    }

    public function showCart()
    {
        return $this->index();
    }

    public function add($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $qty = (int) $request->input('quantity', 1);
        if ($qty < 1) {
            $qty = 1;
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $qty,
                "price" => $product->discount_price ?? $product->price,
                "image" => $product->thumbnail
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $qty = (int) $request->input('quantity', 1);
            if ($qty > 0) {
                $cart[$id]['quantity'] = $qty;
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Cart updated successfully.');
            } else {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Product removed from cart.');
            }
        }
        return redirect()->back();
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    public function buyNow($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $cart = [
            $product->id => [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->discount_price ?? $product->price,
                "image" => $product->thumbnail
            ]
        ];
        session()->put('cart', $cart);
        return redirect()->route('checkout.index');
    }

    public function buyNowCheckout()
    {
        return redirect()->route('checkout.index');
    }
}
