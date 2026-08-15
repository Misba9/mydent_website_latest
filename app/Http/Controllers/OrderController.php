<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    //
public function showOrders()
{
    $orders = Order::latest()->paginate(10); // or whatever fits your front-end use case
    return view('layouts.ecom.orders.index', compact('orders'));
}

public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:Pending,Processing,Shipped,Delivered',
    ]);

    $order->update(['status' => $request->status]);

    return back()->with('success', 'Order status updated.');
}

}
