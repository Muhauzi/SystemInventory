<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventaris;
use App\Models\m_tagihan;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

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

    // Relationships

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function tagihanDenda()
    {
        return $this->hasOne(m_tagihan::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function penanggungJawab()
    {
        return $this->hasOne(m_penanggung_jawab::class, 'id_peminjaman', 'id_peminjaman');
    }

    // Fungsi
    
    public function getPeminjamanById($id)
    {
        return $this->with(['detailPeminjaman.barang', 'user'])
            ->where('id_peminjaman', $id)
            ->first();
    }

    public function getDataPeminjamanBarang($id_barang, $user = null)
    {
        $query = $this->with(['detailPeminjaman.barang', 'user', 'user.detail']);

        // Cek jika user yang melakukan peminjaman memiliki role partnership
        $query = $query->when($user && isset($user->role) && $user->role === 'partnership', function ($q) {
            return $q->with('penanggungJawab');
        });

        return $query->whereHas('detailPeminjaman', function ($q) use ($id_barang) {
                $q->where('id_barang', $id_barang);
            })
            ->where('status', 'Dipinjam')
            ->get()
            ->map(function ($peminjaman) use ($user) {
                $peminjaman->user_name = $peminjaman->user->name ?? null;
                $peminjaman->user_detail = $peminjaman->user->detail ?? null;
                $peminjaman->barang_dipinjam = $peminjaman->detailPeminjaman->map(function ($detail) {
                    return $detail->barang;
                });
                // Jika user yang melakukan peminjaman memiliki role partnership, tambahkan data penanggungJawab
                if ($peminjaman->user && isset($peminjaman->user->role) && $peminjaman->user->role === 'partnership') {
                    $peminjaman->penanggung_jawab = $peminjaman->penanggungJawab;
                }
                return $peminjaman;
            });
    }


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

    public function showPeminjaman($id)
    {
        return $this->with(['detailPeminjaman.barang', 'user'])
            ->where('id_peminjaman', $id)
            ->first();
    }

    public function dataPeminjamanByDetail($id)
    {
        return $this->with(['detailPeminjaman.barang', 'user'])
            ->whereHas('detailPeminjaman', function ($query) use ($id) {
            $query->where('id', $id);
            })
            ->get()
            ->map(function ($peminjaman) {
            $peminjaman->user_name = $peminjaman->user->name ?? null;
            $peminjaman->barang_dipinjam = $peminjaman->detailPeminjaman->map(function ($detail) {
                return $detail->barang;
            });
            return $peminjaman;
            });
    }

    public function dataPeminjamanByUser($id)
    {
        return $this->where('id_user', $id)
            ->get();
    }

    public function detailPeminjamanUser($id)
    {
        return $this->with(['detailPeminjaman.barang', 'user'])
            ->where('id_user', $id)
            ->get();
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
        $data = $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman')
            ->with('barang')
            ->where('id_peminjaman', $id_peminjaman)
            ->get();
        return $data;
    }


    public function getTelatPengembalian($id_user)
    {
        $data = $this->where('id_user', $id_user)
            ->where('status', '!=', 'Dikembalikan')
            ->where('tgl_tenggat', '<', now())
            ->get();
        
        return $data->isNotEmpty() ? $data : false;
    }

    function pengembalianTerlambat($id_user)
    {
        $data = $this->where('id_user', $id_user)
            ->where('status', 'Dikembalikan')
            ->where('tgl_tenggat', '<', now())
            ->get();
        
        foreach ($data as $item) {
            $item->status = 'Terlambat';
        }

        return $data->isNotEmpty() ? $data : false;
    }

    public function getDendaTelatPengembalian($id_user)
    {
        $data = $this->where('id_user', $id_user)
            ->where('status', '!=', 'Dikembalikan')
            ->where('tgl_tenggat', '<', now())
            ->get();

        $telatHari = 0;
        foreach ($data as $item) {
            $telatHari += now()->diffInDays($item->tgl_tenggat);
        }
        if ($telatHari > 0) {
            return $telatHari * 10000; // Denda per hari
        } else {
            return 0;
        }
    }

    public function isLate($id_peminjaman)
    {
        $tagihanDendaExists = $this->tagihanDenda()->where('id_peminjaman', $id_peminjaman)->exists();
        return $tagihanDendaExists ? true : false;
    }

    public function getTagihanKeterlambatan($id_peminjaman)
    {
        $data_tagihan = $this->where('id_peminjaman', $id_peminjaman)
            ->where('tagihanDenda', $id_peminjaman)
            ->first();
        
        return $data_tagihan->tagihanDenda->jumlah_tagihan;
    }
}
