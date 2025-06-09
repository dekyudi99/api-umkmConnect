<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Models\UserProgress;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role', 'bisnis_name', 'path_image'];
    protected $hidden = ['password'];

    // Implementasi JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // biasanya return id
    }

    public function getJWTCustomClaims()
    {
        return [
            // 'role' => $this->role,
            // 'bisnis_name' => $this->bisnis_name,
        ]; // bisa tambahkan data tambahan ke token jika perlu
    }

    public function userProgress() {
        return $this->hasMany(UserProgress::class, 'user_id', 'id');
    }
}