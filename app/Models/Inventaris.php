<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $fillable = [
        'id_barang',
        'nama_barang',
        'status_barang',
        'kondisi',
        'foto_barang',
        'harga_barang',
        'tgl_pembelian',
        'deskripsi_barang',
        'qr_code',
        'id_kategori',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function barangTersedia()
    {
        return $this->where('status', 1)->get();
    }

    public function getNamaBarang($id)
    {
        return $this->where('id_barang', $id)->first();
    }
    public function getInventarisKategori()
    {
        return $this->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('barang.*', 'kategori_barang.nama_kategori')
            ->get();
    }
}
