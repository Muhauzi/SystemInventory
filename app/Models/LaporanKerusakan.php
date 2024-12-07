<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LaporanKerusakan extends Model
{
    protected $table = 'laporan_kerusakan'; // Mendefinisikan nama tabel
    protected $fillable = ['id_detail_peminjaman', 'deskripsi_kerusakan']; // Mendefinisikan kolom yang dapat diisi
    protected $primaryKey = 'id'; // Mendefinisikan primary key

    public $timestamps = true; // Mendefinisikan kolom created_at dan updated_at

    public function getAll()
    {
        return $this->all();
    }

    public function getBarangKategori()
    {
        $kategori = DB::table('laporan_kerusakan')
            ->join('detail_peminjaman', 'detail_peminjaman.id', '=', 'laporan_kerusakan.id_detail_peminjaman')
            ->join('barang', 'barang.id_barang', '=', 'detail_peminjaman.id_barang')
            ->join('kategori_barang', 'kategori_barang.id_kategori', '=', 'barang.id_kategori')
            ->select('laporan_kerusakan.*', 'detail_peminjaman.id_barang', 'detail_peminjaman.id_peminjaman', 'barang.*', 'kategori_barang.*')
            ->get();
        return $kategori;
    }

    public function getLaporanKerusakan()
    {
        $data = DB::table('laporan_kerusakan')
            ->join('detail_peminjaman', 'laporan_kerusakan.id_detail_peminjaman', '=', 'detail_peminjaman.id')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('laporan_kerusakan.*', 'detail_peminjaman.*', 'barang.*', 'peminjaman.*', 'users.*', 'kategori_barang.*')
            ->get();
        return $data;
    }

    public function getDetailKerusakan($id)
    {
        $data = DB::table('laporan_kerusakan')
            ->join('detail_peminjaman', 'laporan_kerusakan.id_detail_peminjaman', '=', 'detail_peminjaman.id')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('laporan_kerusakan.*', 'detail_peminjaman.*', 'barang.*', 'peminjaman.*', 'users.*', 'kategori_barang.*')
            ->where('laporan_kerusakan.id', $id)
            ->first();
        return $data;
    }

    public function getPeminjaman($id)
    {
        $data = DB::table('laporan_kerusakan')
            ->join('detail_peminjaman', 'laporan_kerusakan.id_detail_peminjaman', '=', 'detail_peminjaman.id')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('users_detail', 'users.id', '=', 'users_detail.user_id')
            ->select('laporan_kerusakan.*', 'detail_peminjaman.*', 'peminjaman.*', 'users.*', 'users_detail.*')
            ->where('laporan_kerusakan.id', $id)
            ->first();
        return $data;
    }

    public function getTagihan($id)
    {
        $data = DB::table('laporan_kerusakan')
            ->join('tagihan_kerusakan', 'laporan_kerusakan.id', '=', 'tagihan_kerusakan.id_laporan_kerusakan')
            ->select('laporan_kerusakan.*', 'tagihan_kerusakan.*')
            ->where('laporan_kerusakan.id', $id)
            ->first();
        return $data;
    }

    public function getTagihanAll()
    {
        $data = DB::table('laporan_kerusakan')
            ->join('tagihan_kerusakan', 'laporan_kerusakan.id', '=', 'tagihan_kerusakan.id_laporan_kerusakan')
            ->join('detail_peminjaman', 'detail_peminjaman.id', '=', 'laporan_kerusakan.id_detail_peminjaman')
            ->join('barang', 'barang.id_barang', '=', 'detail_peminjaman.id_barang')
            ->join('kategori_barang', 'kategori_barang.id_kategori', '=', 'barang.id_kategori')
            ->select('laporan_kerusakan.id as id_laporan', 'laporan_kerusakan.*', 'tagihan_kerusakan.*', 'detail_peminjaman.*', 'barang.*', 'kategori_barang.*')
            ->get();
        return $data;
    }
}
