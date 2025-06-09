<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserProgress;

class Contens extends Model 
{
    protected $table = 'contens';
    protected $fillable = [
        'title', 'video', 'description', 'thumnail', 'topic',
    ];

    public function user() {
        return $this->hasMany(UserProgress::class, 'content_id', 'id');
    }
}