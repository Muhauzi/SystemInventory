<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class m_tagihan extends Model
{
    use HasUuids;

    protected $table = 'tagihan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'id_peminjaman',
        'jenis_tagihan',
        'jumlah_tagihan',
        'bukti_pembayaran',
        'status_pembayaran',
        'token',
        'payment_url'
    ];
    public $timestamps = true;

    protected $m_peminjaman;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes); // ini penting!
    
        $this->m_peminjaman = new Peminjaman();
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function getDataPeminjaman()
    {
        return $this->m_peminjaman->all();
    }

    public function getTagihanByPeminjamanId($id)
    {
        return $this->where('id_peminjaman', $id)->first();
    }

    public function getTagihanById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getTagihanByStatus($status)
    {
        return $this->where('status_pembayaran', $status)->get();
    }

    public function getTagihanByUserId($id)
    {
        $data = $this->whereHas('peminjaman', function ($query) use ($id) {
            $query->where('id_user', $id);
        })->with('peminjaman')->get();
        return $data;
    }

    public function getDataTagihanById($id)
    {
        return $this->where('id', $id)
            ->with(['peminjaman', 'peminjaman.detailPeminjaman' => function ($query) {
                $query->with('barang');
            }])
            ->first();
    }

    public function userHasTagihan($id_user)
    {
        return $this->whereHas('peminjaman', function ($query) use ($id_user) {
            $query->where('id_user', $id_user);
        })->whereNotIn('status_pembayaran', ['capture', 'settlement'])->exists();
    }

    public function getJumlahTagihan($id_peminjaman)
    {
        return $this->where('id_peminjaman', $id_peminjaman)->sum('jumlah_tagihan');
    }


}
