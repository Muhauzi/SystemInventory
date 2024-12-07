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


}
