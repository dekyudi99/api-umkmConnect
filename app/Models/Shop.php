<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Products;

class Shop extends Model
{
    protected $table = 'shop';
    protected $fillable = [
        'user_id', 'name', 'foto_ktp', 'fotoProfil_toko', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product() {
        return $this->hasMany(Products::class, 'shop_id', 'id');
    }
}