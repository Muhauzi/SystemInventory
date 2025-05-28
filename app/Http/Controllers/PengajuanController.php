<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Inventaris;
use App\Models\M_detail_pengajuan;
use App\Models\M_pengajuan;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\m_penanggung_jawab; // Pastikan model ini sudah ada jika diperlukan
use Illuminate\Support\Facades\DB;



class PengajuanController extends Controller
{
    protected $m_pengajuan, $m_detail_pengajuan, $m_barang, $m_peminjaman, $m_detail_peminjaman, $m_penanggung_jawab;
    protected $authUser;
    public function __construct()
    {
        $this->m_pengajuan = new M_pengajuan();
        $this->m_detail_pengajuan = new M_detail_pengajuan();
        $this->m_barang = new Inventaris();
        $this->authUser = Auth::user();
        $this->m_peminjaman = new Peminjaman();
        $this->m_detail_peminjaman = new DetailPeminjaman();
        $this->m_penanggung_jawab = new m_penanggung_jawab(); // Inisialisasi model penanggung jawab jika diperlukan
    }

    public function index()
    {
        $pengajuan = $this->m_pengajuan->getAll()->sortByDesc('created_at'); // Mengambil semua data pengajuan peminjaman dan mengurutkannya berdasarkan tanggal dibuat (created_at) secara menurun
        $data = [
            'pengajuan' => $pengajuan,
            'title' => 'Pengajuan'
        ];
        return view('pengajuan.index', $data);
    }

    public function show($id)
    {
        $pengajuan = $this->m_pengajuan->getPengajuanById($id); // Mengambil data pengajuan peminjaman berdasarkan id
        // dd($pengajuan);
        $detail = $this->m_detail_pengajuan->getDetailPengajuanByPengajuanId($id); // Mengambil data detail pengajuan peminjaman berdasarkan id pengajuan
        // dd($detail);
        $barang = []; // Inisialisasi array untuk menyimpan data barang
        foreach ($detail as $item) { // Looping data detail pengajuan
            $barang[] = [
                'id_barang' => $item->id_barang,
                'nama_barang' => $this->m_barang->getNamaBarang($item->id_barang)->nama_barang, // Mengambil nama barang berdasarkan id barang
            ];
        }

        // dd($barang);

        $data = [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'id_user' => $pengajuan->id_user,
            'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
            'tanggal_mulai' => $pengajuan->tanggal_mulai,
            'tanggal_selesai' => $pengajuan->tanggal_selesai,
            'status_pengajuan' => $pengajuan->status_pengajuan,
            'alasan' => $pengajuan->alasan,
            'barang' => $barang,
            'surat_pengantar' => $pengajuan->surat_pengantar,
            'nama_peminjam' => $pengajuan->user->name,
            'keterangan_pengajuan' => $pengajuan->keterangan_pengajuan,
            'is_processed' => $pengajuan->is_processed,
        ];

        // dd($data);
        
        return view('pengajuan.show', compact('data'));
    }

    public function updateStatusPengajuan(Request $request, $id)
    {
        $pengajuan = $this->m_pengajuan->getPengajuanById($id);
        $detailPengajuan = $this->m_detail_pengajuan->getDetailPengajuanByPengajuanId($id);
        $barang = [];
        foreach ($detailPengajuan as $item) {
            $barang[] = [
                'id_barang' => $item->id_barang,
                'nama_barang' => $this->m_barang->getNamaBarang($item->id_barang)->nama_barang,
            ];
        }
        if ($pengajuan) {
            $pengajuan->status_pengajuan = $request->input('status_pengajuan');
            $pengajuan->keterangan_pengajuan = $request->input('keterangan_pengajuan');
            if ($request->input('status_pengajuan') == 'Disetujui') {
                foreach ($barang as $item) {
                    $inventaris = $this->m_barang->getBarangById($item['id_barang']);
                    if ($inventaris) {
                        $inventaris->status_barang = 'Dibooking';
                        $inventaris->save();
                    }
                }
            } elseif ($request->input('status_pengajuan') == 'Ditolak') {
                foreach ($barang as $item) {
                    $inventaris = $this->m_barang->getBarangById($item['id_barang']);
                    if ($inventaris) {
                        $inventaris->status_barang = 'Tersedia';
                        $inventaris->save();
                    }
                }
            }
            $pengajuan->save();
            return redirect()->route('pengajuan.index')->with('success', 'Status pengajuan berhasil diperbarui.');
        } else {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan tidak ditemukan.');
        }
    }
    
    public function destroy($id)
    {
        $pengajuan = $this->m_pengajuan->hapusPengajuan($id);
        if ($pengajuan) {
            return redirect()->route('user.pengajuanPeminjaman')->with('success', 'Pengajuan berhasil dihapus.');
        } else {
            return redirect()->route('user.pengajuanPeminjaman')->with('error', 'Pengajuan tidak ditemukan.');
        }
    }

    public function pengajuanDiambil(Request $request, $id)
    {
        $pengajuan = $this->m_pengajuan->getPengajuanById($id);
        if (!$pengajuan) {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan tidak ditemukan.');
        }
    
        if ($pengajuan->status_pengajuan !== 'Disetujui') {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan tidak disetujui.');
        }
    
        if ($pengajuan->is_processed == true) {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan sudah diproses.');
        }
    
        $barangList = $this->m_detail_pengajuan->getDetailPengajuanByPengajuanId($id);
        if (empty($barangList)) {
            return redirect()->route('pengajuan.index')->with('error', 'Tidak ada barang dalam pengajuan.');
        }
    
        DB::beginTransaction();
    
        try {
            // Buat data peminjaman
            $peminjaman = $this->m_peminjaman->create([
                'id_user' => $pengajuan->id_user,
                'tgl_pinjam' => $pengajuan->tanggal_mulai,
                'tgl_tenggat' => $pengajuan->tanggal_selesai,
                'status' => 'Dipinjam',
                'keterangan' => $pengajuan->alasan
            ]);
    
            if (!$peminjaman) {
                throw new \Exception('Gagal membuat data peminjaman.');
            }
    
            // Simpan id peminjaman
            $id_peminjaman = $peminjaman->id_peminjaman;

            $penanggungJawab = $this->m_penanggung_jawab->create([
                'id_peminjaman' => $id_peminjaman,
                'nama' => $request->nama_pengambil,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jabatan' => $request->jabatan,
                'alamat' => $request->alamat,
            ]);

            if (!$penanggungJawab) {
                throw new \Exception('Gagal menyimpan penanggung jawab peminjaman.');
            }
    
            // Simpan detail peminjaman dan update status barang
            foreach ($barangList as $barang) {
                $this->m_detail_peminjaman->create([
                    'id_peminjaman' => $id_peminjaman,
                    'id_barang' => $barang['id_barang'],
                ]);
    
                $this->m_barang->updateStatusBarang($barang['id_barang'], 'Dipinjam');
            }
    
            // Update status pengajuan
            $pengajuan->is_processed = true;
            $pengajuan->save();
    
            DB::commit();
    
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dibuat.');
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->route('pengajuan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }







}
