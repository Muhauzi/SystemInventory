<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanKerusakan extends Model
{
    protected $table = 'tagihan_kerusakan'; // Mendeklarasikan nama tabel tagihan_kerusakan 

    protected $fillable = [ 
        'id_tagihan', 
        'id_laporan_kerusakan', 
        'status', 
        'total_tagihan', 
        'token',
        'payment_url',
        'created_at', 
        'updated_at'
    ]; // Mendeklarasikan kolom yang dapat diisi

    public function laporan_kerusakan() // Relasi ke tabel laporan_kerusakan
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan_kerusakan', 'id_laporan_kerusakan');
    }


}
