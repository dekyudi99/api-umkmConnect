<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;

class OrderItem extends Model
{
    protected $table = 'order_item';
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price',
    ];

    public function orders() {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}