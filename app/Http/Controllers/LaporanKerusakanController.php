<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use App\Models\FotoKerusakan;
use Midtrans\Config;
use Midtrans\Snap;

class LaporanKerusakanController extends Controller
{
    private $ModelLaporan, $ModelPeminjaman, $ModelDetail, $ModelInventaris, $ModelFotoKerusakan;


    public function __construct()
    {
        $this->ModelLaporan = new LaporanKerusakan();
        $this->ModelPeminjaman = new Peminjaman();
        $this->ModelDetail = new DetailPeminjaman();
        $this->ModelInventaris = new Inventaris();
        $this->ModelFotoKerusakan = new FotoKerusakan();
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function index()
    {
        $laporan_kerusakan   = $this->ModelLaporan->getBarangKategori()->toArray();
        // dd( $laporan_kerusakan);
        return view('laporan.kerusakan.index', compact('laporan_kerusakan'));
    }

    public function detailKerusakan($id)
    {
        $laporan_kerusakan = $this->ModelLaporan->getDetailKerusakan($id);
        $bukti_kerusakan = $this->ModelFotoKerusakan->where('id_laporan_kerusakan', $id)->get()->toArray();
        return view('laporan.kerusakan.show', compact('laporan_kerusakan', 'bukti_kerusakan'));
    }

    public function konfirmasiPenggantian($id)
    {
        $laporan_kerusakan = LaporanKerusakan::findOrFail($id);
        $detail_peminjaman = DetailPeminjaman::where('id', $laporan_kerusakan->id_detail_peminjaman)->first();
        $inventaris = Inventaris::where('id', $detail_peminjaman->id_inventaris)->first();
        return view('laporan_kerusakan.konfirmasi_penggantian', compact('laporan_kerusakan', 'detail_peminjaman', 'inventaris'));
    }

    public function formSubmitKerusakan($id)
    {
        $PeminjamanModel = new Peminjaman();
        $id_detail = DetailPeminjaman::where('id_peminjaman', $id)->first();
        $ModelDetail = new DetailPeminjaman();
        $dataPeminjam = $ModelDetail->getDetail($id_detail->id);

        return view('laporan.kerusakan.form_submit', compact('dataPeminjam'));
    }

    public function submitKerusakan(Request $request)
    {
        $data = $request->all();
        $modelLaporanKerusakan = new LaporanKerusakan();
        $modelFotoKerusakan = new FotoKerusakan();

        $modelLaporanKerusakan->create([
            'id_detail_peminjaman' => $data['id_detail_peminjaman'],
            'deskripsi_kerusakan' => $data['deskripsi_kerusakan'], 
        ]);     

        $id_laporan_kerusakan = $modelLaporanKerusakan->latest()->first()->id;

        if ($request->hasFile('foto_kerusakan')) {
            $files = $request->file('foto_kerusakan');
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->storePublicly('buktiKerusakan', 'public');
                $modelFotoKerusakan->create([
                    'id_laporan_kerusakan' => $id_laporan_kerusakan,
                    'foto' => $filename,
                ]);
            }
        }   

        return redirect()->route('peminjaman.index');
    }


}
