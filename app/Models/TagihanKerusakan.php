<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class TagihanKerusakan extends Model
{
    Use HasUuids;
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
        'nota_perbaikan',
        'created_at',
        'updated_at'
    ]; // Mendeklarasikan kolom yang dapat diisi
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function laporan_kerusakan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan_kerusakan');
    }

    public function getLaporanPeminjaman($id) // Mendapatkan data laporan kerusakan berdasarkan id
    {
        return $this->where('id_laporan_kerusakan', $id)
            ->with([
                'laporan_kerusakan.detailPeminjaman.peminjaman.user',
                'laporan_kerusakan.detailPeminjaman.barang.kategoriBarang',
                'laporan_kerusakan.fotoKerusakan'
            ])
            ->get();
    }

    public function getTagihanKerusakanById($id) // Mendapatkan data tagihan kerusakan berdasarkan id
    {
        return $this->where('id', $id)
            ->with([
                'laporan_kerusakan.detailPeminjaman.peminjaman.user',
                'laporan_kerusakan.detailPeminjaman.barang',
                'laporan_kerusakan.detailPeminjaman.barang.kategoriBarang',
                'laporan_kerusakan.foto_kerusakan'
            ])
            ->first();
    }

    public function getTagihanKerusakanByUserId($id_user) // Mendapatkan data tagihan kerusakan berdasarkan id user
    {
        return $this->whereHas('laporan_kerusakan', function ($query) use ($id_user) {
            $query->whereHas('detailPeminjaman.peminjaman', function ($query) use ($id_user) {
            $query->where('peminjaman.id_user', $id_user);
            });
        })->with(['laporan_kerusakan.detailPeminjaman.peminjaman', 'laporan_kerusakan.detailPeminjaman.barang'])->get();    
    }

    public function userHasTagihan($id_user)
    {
        return $this->whereHas('laporan_kerusakan', function ($query) use ($id_user) {
            $query->whereHas('detailPeminjaman.peminjaman', function ($query) use ($id_user) {
                $query->where('peminjaman.id_user', $id_user);
            });
        })->whereNotIn('status', ['capture', 'settlement'])->exists();
    }
}
