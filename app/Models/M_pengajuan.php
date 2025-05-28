<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;


class M_pengajuan extends Model
{
    use HasUuids;
    protected $table = 'pengajuan_peminjaman';
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    

    protected $fillable = [
        'id_pengajuan',
        'id_user',
        'tanggal_pengajuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_pengajuan',
        'alasan',
        'surat_pengantar',
        'keterangan_pengajuan',
        'is_processed',
        'created_at',
        'updated_at'
        ];

    public function detailPengajuan()
    {
        return $this->hasMany(M_detail_pengajuan::class, 'id_pengajuan', 'id_pengajuan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function getPengajuanById($id)
    {
        return $this->with('detailPengajuan','user')->where('id_pengajuan', $id)->first();
    }
    public function getPengajuanByUserId($id)
    {
        return $this->where('id_user', $id)->get();
    }

    public function getAll()
    {
        return $this->all();
    }

    public function hapusPengajuan($id)
    {
        $pengajuan = $this->where('id_pengajuan', $id)
                          ->with('detailPengajuan')
                          ->where('id_pengajuan', $id)
                          ->first();
        if ($pengajuan) {
            $pengajuan->detailPengajuan()->delete();
            return $pengajuan->delete();
        }
        return false;
    }
}
