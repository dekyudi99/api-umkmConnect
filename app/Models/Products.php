<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Orders;

class Products extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'user_id', 'title', 'description', 'price', 'stock', 'image',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order() {
        return $this->hasOne(Orders::class, 'product_id', 'id');
    }
}