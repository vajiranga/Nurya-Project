<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name', 'phone', 'address', 
        'total_amount', 'items_json', 
        'status', 'payment_method'
    ];

    protected $casts = [
        'items_json' => 'array',
    ];
}
