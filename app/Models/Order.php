<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'address',
        'country', 'state', 'city', 'pincode',
        'payment_method', 'products', 'subtotal',
        'tax', 'total','status',
    ];

    protected $casts = [
        'products' => 'array',
    ];
}

