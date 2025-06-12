<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Payments;

class Orders extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id', 'total_price', 'payment_status', 'payment_method',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderItem() {
        return $this->hasOne(OrderItem::class, 'order_id', 'id');
    }

    public function payments() {
        return $this->hasOne(Payments::class, 'order_id', 'id');
    }
}