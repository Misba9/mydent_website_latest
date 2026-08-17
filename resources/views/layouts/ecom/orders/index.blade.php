@extends('layouts.app')
@section('title')
    Orders Management
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="mb-0">Customer Orders</h1>
        </div>
        <div class="card">
            <div class="card-body">
                @if(isset($orders) && $orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $order->name }}</div>
                                            <small class="text-muted">{{ $order->email }}</small>
                                        </td>
                                        <td>{{ $order->phone }}</td>
                                        <td class="fw-bold text-primary">{{ getCurrencyIcon() }}{{ number_format($order->total, 2) }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $order->payment_method }}</span></td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_status == 'Paid' ? 'success' : 'warning' }}">
                                                {{ $order->payment_status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->status == 'Delivered' ? 'success' : ($order->status == 'Shipped' ? 'info' : 'secondary') }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '' }}</td>
                                        <td>
                                            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                                    <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                                    <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                                    <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">No orders found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
