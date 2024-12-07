<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoKerusakan extends Model
{
    protected $table = 'foto_kerusakan'; // Mendefinisikan nama tabel
    protected $fillable = ['id_laporan_kerusakan', 'foto', 'created_at', 'updated_at']; // Mendefinisikan kolom yang dapat diisi
    protected $primaryKey = 'id'; // Mendefinisikan primary key

    public function laporan_kerusakan() // Relasi ke model LaporanKerusakan
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan_kerusakan', 'id'); // Menentukan relasi ke model LaporanKerusakan
    }
}
