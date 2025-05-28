<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Inventaris extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'id_barang',
        'nama_barang',
        'status_barang',
        'jenis_barang',
        'kondisi',
        'foto_barang',
        'harga_barang',
        'tgl_pembelian',
        'deskripsi_barang',
        'qr_code',
        'id_kategori',
        'created_at',
        'updated_at'
    ];



    public $timestamps = true;

    protected $m_kategori, $m_detail_pengajuan;

    public function __construct() {
        $this->m_kategori = new KategoriBarang();
        $this->m_detail_pengajuan = new M_detail_pengajuan();
    }

    public function kategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class, 'id_kategori', 'id_kategori');
    }
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_barang', 'id_barang');
    }

    public function getPeminjamBarang($id_barang)
    {
        return $this->where('id_barang', $id_barang)
            ->with(['detailPeminjaman.peminjaman.user', 'detailPeminjaman.peminjaman.penanggungJawab'])
            ->get();
    }

    public function barangTersedia()
    {
        if(Auth::user()->role == 'partnership') {
            return self::with('kategoriBarang')
            ->where('jenis_barang', 'Barang Pinjam')
            ->where('status_barang', 'Tersedia')
            ->get();
        } 

        return self::with('kategoriBarang')
            ->where('status_barang', 'Tersedia')
            ->get();
    }

    public function getNamaBarang($id)
    {
        return $this->where('id_barang', $id)->select('nama_barang')->first();
    }
    public function getInventarisKategori()
    {
        return $this->join('kategori_barang', 'barang.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('barang.*', 'kategori_barang.nama_kategori')
            ->get();
    }

    public function getBarangById($id)
    {
        return $this->where('id_barang', $id)->first();
    }

    public function getDataBarang($id)
    {
        $barang = $this->where('id_barang', $id)->first();
        if ($barang) {
            $kategori = $this->m_kategori->getKodeKategori($barang->id_kategori);
            return [
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'status_barang' => $barang->status_barang,
                'jenis_barang' => $barang->jenis_barang,
                'kondisi' => $barang->kondisi,
                'foto_barang' => $barang->foto_barang,
                'harga_barang' => $barang->harga_barang,
                'tgl_pembelian' => $barang->tgl_pembelian,
                'deskripsi_barang' => $barang->deskripsi_barang,
                'qr_code' => $barang->qr_code,
                'kategori' => $kategori
            ];
        }
    }

    public function getDataBarangUser($id)
    {
        $barang = $this->where('id_barang', $id)->first();
        if ($barang) {
            $kategori = $this->m_kategori->getNamaKategori($barang->id_kategori);
            return [
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'foto_barang' => $barang->foto_barang,
                'harga_barang' => $barang->harga_barang,
                'deskripsi_barang' => $barang->deskripsi_barang,
                'kategori' => $kategori
            ];
        }
    }

    public function isBooked($id)
    {
        $barang = $this->where('id_barang', $id)->first();
        if ($barang) {
            return $barang->status_barang === 'Dipinjam';
        }
        return false;
    }

    public function deleteBarang($barang)
    {
        $barang = $this->where('id_barang', $barang)->first();
        if ($barang) {
            $this->m_detail_pengajuan->deleteDetailPengajuan($barang->id_barang);
            return $barang->delete();
        }
        return false;
    }

    public function updateStatusBarang($id_barang, $status)
    {
        $barang = $this->where('id_barang', $id_barang)->first();
        if ($barang) {
            $barang->status_barang = $status;
            return $barang->save();
        }
        return false;
    }
}
