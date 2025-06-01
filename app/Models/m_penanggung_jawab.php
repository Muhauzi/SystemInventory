<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class m_penanggung_jawab extends Model
{
    protected $table = 'penanggung_jawab';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'id',
        'id_peminjaman',
        'nama',
        'email',
        'no_hp',
        'jabatan',
        'alamat',
    ];
    public $timestamps = true;
    
    public function peminjaman(){
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id');
    }

    public function getPJByPeminjaman($id_peminjaman)
    {
        $data = $this->where('id_peminjaman', $id_peminjaman)->get();
        if ($data->isEmpty()) {
            return null; // Atau bisa mengembalikan array kosong jika tidak ada data
        }
        dd($data);
        return $data;
    }
}
