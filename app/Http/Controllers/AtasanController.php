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
        $peminjaman->status = $request->status;
        $peminjaman->save();
        return redirect()->route('pimpinan.izin_peminjaman')->with('success', 'Izin telah diberikan.');
    }

    public function downloadLaporanKerusakan()
    {
        $modelKerusakan = new LaporanKerusakan();
        // make broken report word
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Laporan Kerusakan Barang', ['bold' => true, 'size' => 16]);
        $section->addTextBreak(1);
        $section->addText('Berikut adalah laporan kerusakan barang yang telah terjadi:', ['size' => 12]);
        $section->addTextBreak(1);
        $dataKerusakan = $modelKerusakan->getLaporanKerusakan();
        foreach ($dataKerusakan as $data) {
            $section->addText('ID Laporan: ' . $data->id, ['bold' => true]);
            $section->addText('ID Peminjaman: ' . $data->id_peminjaman);
            $section->addText('ID Barang: ' . $data->id_barang);
            $section->addText('Nama Barang: ' . $data->nama_barang);
            $section->addText('Kategori Barang: ' . $data->nama_kategori);
            $section->addText('Nama Peminjam: ' . $data->name);
            $section->addText('Deskripsi Kerusakan: ' . $data->deskripsi_kerusakan);
            $section->addText('Tanggal Laporan: ' . $data->created_at);
            $section->addTextBreak(1);
        }
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Laporan Kerusakan Barang.docx');
        return response()->download(public_path('Laporan Kerusakan Barang.docx'));
    }                                                                                       


}
