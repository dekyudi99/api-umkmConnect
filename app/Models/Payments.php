<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;

class Payments extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'order_id', 'payment_gateway', 'paid_at',
    ];

    public function order() {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}