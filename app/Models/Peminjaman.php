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
            ->join('inventaris', 'detail_peminjaman.id_barang', '=', 'inventaris.id_barang')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('peminjaman.*', 'detail_peminjaman.*', 'inventaris.*', 'users.name', 'users.email', 'users.id')
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
    
}
