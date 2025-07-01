<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserProgress;

class Contens extends Model 
{
    protected $table = 'contents';
    protected $fillable = [
        'title', 'video', 'description', 'creator', 'playlist', 'thumbnail',
    ];

    public function user() {
        return $this->hasMany(UserProgress::class, 'content_id', 'id');
    }
}