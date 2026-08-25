<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const PAYMENT_STATUS_PENDING = 'Pending';
    const PAYMENT_STATUS_PAID    = 'Paid';
    const PAYMENT_STATUS_FAILED  = 'Failed';
    const PAYMENT_STATUS_COD     = 'COD';

    protected $fillable = [
        'name', 'phone', 'email', 'address',
        'country', 'state', 'city', 'pincode',
        'payment_method', 'products', 'subtotal',
        'tax', 'total', 'status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'payment_status',
    ];

    protected $casts = [
        'products' => 'array',
    ];
}
