<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang';
    protected $primaryKey = 'id_kategori';
    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;

    public function barang()
    {
        return $this->hasMany(Inventaris::class, 'id_kategori', 'id_kategori');
    }

    public function getKategoriById($id)
    {
        return KategoriBarang::find($id);
    }

    public function getKodeKategori($id)
    {
        $kategori = KategoriBarang::find($id);
        return $kategori->kode_kategori;
    }

    public function getNamaKategori($id)
    {
        $kategori = KategoriBarang::find($id);
        return $kategori->nama_kategori;
    }

    public function isUsed($id_kategori)
    {
        $kategori = KategoriBarang::find($id_kategori);
        if ($kategori) {
            return $kategori->barang()->exists(); // Mengecek apakah kategori digunakan
        }
        return false; // Kategori tidak ditemukan
    }
}
