<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_detail_pengajuan extends Model
{
    protected $table = 'detail_pengajuan_peminjaman';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'id_pengajuan',
        'id_barang',
        'created_at',
        'updated_at'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(M_pengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function barang()
    {
        return $this->belongsTo(Inventaris::class, 'id_barang', 'id_barang');
    }

    public function getDetailPengajuanById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getDetailPengajuanByPengajuanId($id)
    {
        return $this->where('id_pengajuan', $id)->get();
    }

    public function getBarangById($id)
    {
        return $this->where('id_barang', $id)->with('barang')->first();
    }

    public function bookedBarang()
    {
        return self::pluck('id_barang');
    }
    

}
