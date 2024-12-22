<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatasPeminjaman extends Model
{
    protected $table = 'batas_peminjaman';
    protected $fillable = ['id', 'batas_nominal'];
    public $timestamps = false;
}
