<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailUsers extends Model
{
    protected $table = 'users_detail';
    protected $fillable = [
        'user_id',
        'phone',
        'department',
        'profile_image',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}