<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventaris;
use Illuminate\Support\Facades\DB;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $fillable = [
        'id_user',
        'tgl_pinjam',
        'tgl_tenggat',
        'tgl_kembali',
        'keterangan',
        'status',
        'created_at',
        'updated_at'
    ];
    public $timestamps = true;
    
    public function dataPeminjaman()
    {
        $data = DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('peminjaman.*', 'detail_peminjaman.*', 'barang.*', 'users.name', 'users.email', 'users.id', 'peminjaman.created_at as created_at_peminjaman', 'peminjaman.updated_at as updated_at_peminjaman')
            ->get();
        return $data;
    }       

    public function dataPeminjamanByDetail($id)
    {
        $data = DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->join('inventaris', 'detail_peminjaman.id_barang', '=', 'inventaris.id_barang')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('peminjaman.*', 'detail_peminjaman.*', 'inventaris.*', 'users.name', 'users.email', 'users.id')
            ->where('detail_peminjaman.id', $id)
            ->get();
        return $data;
    }
    
    public function laporanPeminjamanByTahun($tahun)
    {
        $data = DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('peminjaman.*', 'detail_peminjaman.*', 'barang.*', 'users.name', 'users.email', 'users.id', 'kategori_barang.*')
            ->whereYear('detail_peminjaman.created_at', $tahun)
            ->get();

        if ($data->isEmpty()) {
            return false;
        }

        return $data;
    }

    public function laporanPeminjamanByBulan($bulan)
    {
        $data = DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('peminjaman.*', 'detail_peminjaman.*', 'barang.*', 'users.name', 'users.email', 'users.id', 'kategori_barang.*')
            ->whereMonth('detail_peminjaman.created_at', $bulan)
            ->get();
        if ($data->isEmpty()) {
            return false;
        }

        return $data;
    }

    public function peminjamanDitolak($id)
    {
        $data = DB::table('peminjaman')
            ->join('detail_peminjaman', 'peminjaman.id_peminjaman', '=', 'detail_peminjaman.id_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->select('peminjaman.*', 'detail_peminjaman.*', 'barang.*')
            ->where('peminjaman.id_peminjaman', $id)
            ->get();
        return $data;
    }

    public function getDetailPeminjaman($id_peminjaman)
    {
        $data = DB::table('detail_peminjaman')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->select('detail_peminjaman.*', 'barang.*')
            ->where('id_peminjaman', $id_peminjaman)
            ->get();
        return $data;
    }
}
