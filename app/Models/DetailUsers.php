<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailUsers extends Model
{
    protected $table = 'users_detail';
    protected $fillable = [
        'user_id',
        'about',
        'phone',
        'department',
        'profile_image',
        'address',
    ];

    public function user()
    {
        $data = $this->table('users_detail')
            ->join('users', 'users_detail.user_id', '=', 'users.id')
            ->where('users.role', '!=', 'admin')
            ->select('users_detail.*', 'users.*')
            ->get();
        return $data;
    }
}
