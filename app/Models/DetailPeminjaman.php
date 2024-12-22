<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Peminjaman;
use App\Models\Inventaris;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\DB;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_peminjaman',
        'id_barang',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id');
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_barang', 'id');
    }

    public function laporan_kerusakan()
    {
        return $this->hasOne(LaporanKerusakan::class, 'id_detail_peminjaman', 'id');
    }

    public function getDetail($id)
    {
        $data = DB::table('detail_peminjaman')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->select('detail_peminjaman.*', 'peminjaman.*', 'barang.*')
            ->where('peminjaman.id_peminjaman', $id)
            ->get();
        return $data;
    }

    public function getBarangByDetail($id)
    {
        $data = DB::table('detail_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->select('detail_peminjaman.*', 'barang.*')
            ->where('detail_peminjaman.id', $id)
            ->get();
        return $data;
    }

    public function getPeminjam($id) 
    {
        $data = DB::table('detail_peminjaman')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('detail_peminjaman.*', 'peminjaman.*', 'users.*', 'users.id as id_user')
            ->where('detail_peminjaman.id', $id)
            ->get();
        return $data;
    }


    // cek apakah id barang ada di tabel detail peminjaman
    public function isBorrowed()
    {
        $data = DB::table('detail_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->select('detail_peminjaman.*', 'barang.*')
            ->get();

        return $data;
    }

    public function getIdBarangByIdPeminjaman($id_peminjaman)
    {
        $data = DB::table('detail_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->select('detail_peminjaman.*', 'barang.*')
            ->where('id_peminjaman', $id_peminjaman)
            ->get();
        return $data;
    }

    public function getBarangDikembalikan()
    {
        $data = DB::table('detail_peminjaman')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori') 
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('detail_peminjaman.*', 'peminjaman.*', 'barang.*', 'kategori_barang.*', 'peminjaman.updated_at as updated_at_peminjaman', 'users.*')
            ->where('peminjaman.status', 'Dikembalikan')
            ->get();
        return $data;
    }
    public function getBarangDipinjam()
    {
        $data = DB::table('detail_peminjaman')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori') 
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('detail_peminjaman.*', 'peminjaman.*', 'barang.*', 'kategori_barang.*', 'peminjaman.updated_at as updated_at_peminjaman', 'users.*')
            ->where('peminjaman.status', 'Dipinjam')
            ->get();
        return $data;
    }

    public function cekLaporanKerusakan($id)
    {
        $data = DB::table('detail_peminjaman')
            ->join('laporan_kerusakan', 'detail_peminjaman.id', '=', 'laporan_kerusakan.id_detail_peminjaman')
            ->select('detail_peminjaman.*', 'laporan_kerusakan.*')
            ->where('laporan_kerusakan.id_detail_peminjaman', $id)
            ->get();
            
        if ($data->isEmpty()) {
            return false;
        } else {
            return true;
        } 
    }
}
