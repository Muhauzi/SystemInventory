<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class LaporanKerusakan extends Model
{
    protected $table = 'laporan_kerusakan'; // Mendefinisikan nama tabel
    protected $fillable = ['id_detail_peminjaman', 'deskripsi_kerusakan']; // Mendefinisikan kolom yang dapat diisi
    protected $primaryKey = 'id'; // Mendefinisikan primary key
    public $incrementing = true; // Mengatur incrementing menjadi false karena kita menggunakan UUID

    public $timestamps = true; // Mendefinisikan kolom created_at dan updated_at


    public function getAll()
    {
        return $this->all();
    }

    public function detailPeminjaman()
    {
        return $this->belongsTo(DetailPeminjaman::class, 'id_detail_peminjaman');
    }

    public function foto_kerusakan()
    {
        return $this->hasMany(FotoKerusakan::class, 'id_laporan_kerusakan');
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
    
    public function getKategoriDanBarang()
    {
        return $this->with([
            'detailPeminjaman.barang.kategoriBarang', 'detailPeminjaman.peminjaman.user'
        ])->get();
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

    public function getLaporanKerusakanById($id_user)
    {
        $data = DB::table('laporan_kerusakan')
            ->join('detail_peminjaman', 'laporan_kerusakan.id_detail_peminjaman', '=', 'detail_peminjaman.id')
            ->join('barang', 'detail_peminjaman.id_barang', '=', 'barang.id_barang')
            ->join('peminjaman', 'detail_peminjaman.id_peminjaman', '=', 'peminjaman.id_peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('laporan_kerusakan.*', 'detail_peminjaman.*', 'barang.*', 'peminjaman.*', 'users.*', 'kategori_barang.*')
            ->where('peminjaman.id_user', $id_user)
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
            ->where('laporan_kerusakan.id', $id)
            ->select('laporan_kerusakan.*', 'detail_peminjaman.*', 'peminjaman.*', 'users.*')
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

    

    public function laporanKerusakanByBulan($bulan)
    {
        $data = self::with([
                'detailPeminjaman.barang.kategoriBarang',
                'detailPeminjaman.peminjaman.user',
                'foto_kerusakan',
                'tagihanKerusakan'
            ])
            ->whereMonth('created_at', $bulan)
            ->get();

        // if data not found
        if ($data->isEmpty()) {
            return false;
        }

        $data->transform(function ($item) {
            // Flatten nested relationships for easier access
            $item->id_laporan_kerusakan = $item->id;
            $item->nama_peminjam = optional($item->detailPeminjaman->peminjaman->user)->name ?? null;
            $item->nama_barang = optional($item->detailPeminjaman->barang)->nama_barang ?? null;
            $item->kategori_barang = optional($item->detailPeminjaman->barang->kategoriBarang)->nama_kategori ?? null;
            $item->deskripsi_kerusakan = $item->deskripsi_kerusakan ?? null;
            $item->tanggal_laporan = $item->created_at ? $item->created_at->format('Y-m-d') : null;
            $item->status_barang = optional($item->detailPeminjaman->barang)->status_barang ?? null;
            $item->biaya_ganti_rugi = optional($item->tagihanKerusakan)->total_tagihan ?? null;
            $item->status_pembayaran = optional($item->tagihanKerusakan)->status ?? null;
            return $item;
        });

        return $data;
    }

    // Relationship to TagihanKerusakan
    public function tagihanKerusakan()
    {
        return $this->hasOne(TagihanKerusakan::class, 'id_laporan_kerusakan');
    }

    public function laporanKerusakanByTahun($tahun)
    {
        $data = self::with([
                'detailPeminjaman.barang.kategoriBarang',
                'detailPeminjaman.peminjaman.user',
                'foto_kerusakan',
                'tagihanKerusakan'
            ])
            ->whereYear('created_at', $tahun)
            ->get();

        // if data not found
        if ($data->isEmpty()) {
            return false;
        }

        $data->transform(function ($item) {
            // Flatten nested relationships for easier access
            $item->nama_barang = optional($item->detailPeminjaman->barang)->nama_barang ?? null;
            $item->nama_kategori = optional($item->detailPeminjaman->barang->kategoriBarang)->nama_kategori ?? null;
            $item->nama_peminjam = optional($item->detailPeminjaman->peminjaman->user)->name ?? null;
            $item->foto_kerusakan = $item->foto_kerusakan ?? [];
            $item->tagihan = $item->tagihanKerusakan ?? null;
            return $item;
        });

        return $data;
    }
}
