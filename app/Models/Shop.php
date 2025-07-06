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

    protected $appends = ['foto_profil_toko_url', 'foto_ktp_url']; // Tambahkan ini

    public function getFotoProfilTokoUrlAttribute()
    {
        if ($this->fotoProfil_toko) {
            // Asumsi Anda menyimpan di public/uploads/toko
            return ('https://e2f3-182-253-163-199.ngrok-free.app/umkmconnect/public/uploads/toko/' . $this->fotoProfil_toko);
        }
        return null;
    }

    public function getFotoKtpUrlAttribute()
    {
        if ($this->foto_ktp) {
            // Asumsi Anda menyimpan di public/uploads/toko
            return ('https://e2f3-182-253-163-199.ngrok-free.app/umkmconnect/public/uploads/foto_ktp/' . $this->foto_ktp);
        }
        return null;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product() {
        return $this->hasMany(Products::class, 'shop_id', 'id');
    }
}