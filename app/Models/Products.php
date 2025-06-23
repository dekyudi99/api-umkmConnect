<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\Order_Item;
use App\Models\Cart;

class Products extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'shop_id', 'title', 'description', 'location', 'category', 'price', 'stock', 'image', 'rating'
    ];

    public function user() {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function orderItem() {
        return $this->hasMany(Order_Item::class, 'product_id', 'id');
    }

    public function cart() {
        return $this->hasMany(Cart::class, 'product_id', 'id');
    }
}