<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DetailUsers;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\LaporanKerusakan;
use App\Models\Inventaris;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;


class AtasanController extends Controller
{
    public function listPegawai()
    {
        $detail = new DetailUsers();
        $dataPegawai = $detail->user();
        return view('atasan.list_pegawai', compact('dataPegawai'));
    }

    public function detailPegawai($id)
    {
        $detail = new DetailUsers();
        $dataPegawai = $detail->user();
        $pegawai = User::find($id);
        return view('atasan.detail_pegawai', compact('pegawai', 'dataPegawai'));
    }

    public function izinPeminjamanInventaris()
    {
        $peminjaman = new Peminjaman();
        $dataPeminjaman = $peminjaman->where('status', 'pending')->get();
        $users = User::all();
        return view('atasan.list_izin_peminjaman', compact('dataPeminjaman', 'users'));
    }

    public function detailIzinPeminjamanInventaris($id)
    {
        $peminjaman = Peminjaman::find($id); // Mengambil data peminjaman berdasarkan id
        $detailPeminjaman = (new DetailPeminjaman())->getDetail($peminjaman->id_peminjaman); // Mengambil data detail peminjaman berdasarkan id peminjaman
        $users = User::find($peminjaman->id_user); // Mengambil data user berdasarkan id user
        $barang = Inventaris::whereIn('id_barang', $detailPeminjaman->pluck('id_barang'))->get();   // Mengambil data barang berdasarkan id barang
        $nilai = $barang->sum('harga_barang'); // Menjumlahkan nilai barang yang dipinjam


        if (!$peminjaman) { // Jika data peminjaman tidak ditemukan
            return redirect()->back()
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }
        return view('atasan.detail_izin_peminjaman', compact('peminjaman', 'users', 'detailPeminjaman', 'nilai'));
    }

    public function updateIzinPeminjamanInventaris(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return redirect()->route('pimpinan.izin_peminjaman')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $peminjaman->status = $request->status;
        $peminjaman->save();

        $detailPeminjaman = DetailPeminjaman::where('id_peminjaman', $id)->get();
        $barangIds = $detailPeminjaman->pluck('id_barang');
        $barang = Inventaris::whereIn('id_barang', $barangIds)->get();

        if ($request->status == 'Ditolak') {
            foreach ($barang as $b) {
                $b->status_barang = 'Tersedia';
                $b->save();
            }
        }

        switch ($request->status) {
            case 'Disetujui':
                return redirect()->route('pimpinan.izin_peminjaman')->with('success', 'Izin telah diberikan.');
            case 'Ditolak':
                return redirect()->route('pimpinan.izin_peminjaman')->with('success', 'Izin telah ditolak.');
            default:
                return redirect()->route('pimpinan.izin_peminjaman')->with('error', 'Status tidak valid.');
        }
    }
}
