<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TagihanKerusakan extends Model
{
    protected $table = 'tagihan_kerusakan'; // Mendeklarasikan nama tabel tagihan_kerusakan 
    protected $primaryKey = 'id'; // Mendeklarasikan primary key tabel tagihan_kerusakan

    protected $fillable = [
        'id',
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

    public function getLaporanPeminjaman($id) // Mendapatkan data laporan kerusakan berdasarkan id
    {
        return DB::table('tagihan_kerusakan')
            ->join('laporan_kerusakan', 'tagihan_kerusakan.id_laporan_kerusakan', '=', 'laporan_kerusakan.id')
            ->join('detail_peminjaman', 'laporan_kerusakan.id_detail_peminjaman', '=', 'detail_peminjaman.id')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('tagihan_kerusakan.*', 'laporan_kerusakan.*', 'detail_peminjaman.*', 'peminjaman.*', 'barang.*', 'users.*')
            ->where('tagihan_kerusakan.id', $id)
            ->first();
    }

    public function getTagihanKerusakanByUserId($id) // Mendapatkan data tagihan kerusakan berdasarkan id user
    {
        return DB::table('tagihan_kerusakan')
            ->join('laporan_kerusakan', 'tagihan_kerusakan.id_laporan_kerusakan', '=', 'laporan_kerusakan.id')
            ->join('detail_peminjaman', 'laporan_kerusakan.id_detail_peminjaman', '=', 'detail_peminjaman.id')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('tagihan_kerusakan.*', 'laporan_kerusakan.*', 'detail_peminjaman.*', 'peminjaman.*', 'barang.*', 'users.*', 'tagihan_kerusakan.status as status_tagihan')
            ->where('users.id', $id)
            ->get();
    }
}
