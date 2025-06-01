<?php

namespace App\Http\Controllers;

use App\Mail\TagihanPenggantian;
use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Inventaris;
use App\Models\FotoKerusakan;
use App\Models\TagihanKerusakan;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        $laporan_kerusakan   = $this->ModelLaporan->getKategoriDanBarang();
        // dd( $laporan_kerusakan);
        $tagihan = TagihanKerusakan::all();
        return view('laporan.kerusakan.index', compact('laporan_kerusakan', 'tagihan'));
    }

    public function detailKerusakan($id)
    {
        $laporan_kerusakan = $this->ModelLaporan->getDetailKerusakan($id);
        $tagihan = TagihanKerusakan::where('id_laporan_kerusakan', $id)->first();
        $bukti_kerusakan = $this->ModelFotoKerusakan->where('id_laporan_kerusakan', $id)->get()->toArray();
        return view('laporan.kerusakan.show', compact('laporan_kerusakan', 'bukti_kerusakan', 'tagihan'));
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
        $ModelDetail = new DetailPeminjaman();
        $dataPeminjam = $ModelDetail->getBarangByDetail($id);

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
                $file->move('storage/buktiKerusakan', $filename);
                $modelFotoKerusakan->create([
                    'id_laporan_kerusakan' => $id_laporan_kerusakan,
                    'foto' => $filename,
                ]);
            }
        }

        return redirect()->route('peminjaman.index')->with('success', 'Laporan kerusakan berhasil dibuat.');
    }

    public function editKerusakan($id)
    {
        $laporan_kerusakan = $this->ModelLaporan->getDetailKerusakan($id);
        return view('laporan.kerusakan.edit', compact('laporan_kerusakan'));
    }

    public function updateKerusakan(Request $request, $id)
    {
        $data = $request->all();
        $modelLaporanKerusakan = new LaporanKerusakan();
        $modelFotoKerusakan = new FotoKerusakan();

        $modelLaporanKerusakan->where('id', $id)->update([
            'deskripsi_kerusakan' => $data['deskripsi_kerusakan'],
        ]);

        $id_laporan_kerusakan = $id;

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

        return redirect()->route('laporan_kerusakan.index');
    }

    public function createTagihan($id)
    {
        $laporan_kerusakan = LaporanKerusakan::findOrFail($id);
        $detail_peminjaman = DetailPeminjaman::where('id', $laporan_kerusakan->id_detail_peminjaman)->first();
        $inventaris = Inventaris::where('id', $detail_peminjaman->id_inventaris)->first();
        return view('laporan.kerusakan.create_tagihan', compact('laporan_kerusakan', 'detail_peminjaman', 'inventaris'));
    }

    public function storeTagihan(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate(
                [
                    'id_lk' => 'required|exists:laporan_kerusakan,id',
                    'biaya_perbaikan' => 'required|numeric',
                    'nota_perbaikan' => 'required|mimes:jpg,jpeg,png|max:2048',
                ],
                [
                    'id_lk.required' => 'ID Laporan Kerusakan harus diisi.',
                    'id_lk.exists' => 'ID Laporan Kerusakan tidak ditemukan.',
                    'biaya_perbaikan.required' => 'Biaya perbaikan harus diisi.',
                    'biaya_perbaikan.numeric' => 'Biaya perbaikan harus berupa angka.',
                    'nota_perbaikan.mimes' => 'File nota perbaikan harus berupa jpg, jpeg, png.',
                    'nota_perbaikan.max' => 'File nota perbaikan tidak boleh lebih dari 2MB.',
                    'nota_perbaikan.required' => 'File nota perbaikan harus diunggah.',
                ]
            );

            $idLK = $request->id_lk;
            $total_harga = $request->biaya_perbaikan;
            $modelTagihan = new TagihanKerusakan();

            if ($modelTagihan->where('id_laporan_kerusakan', $idLK)->first()) {
                return redirect()->back()->with('error', 'Tagihan sudah dibuat.');
            }

            $dataInput = [
                'id_laporan_kerusakan' => $idLK,
                'total_tagihan' => $total_harga,
                'status' => 'pending',
            ];

            $nota_pembayaran = $request->file('nota_perbaikan');
            if ($nota_pembayaran) {
                $filename = time() . '_' . $nota_pembayaran->getClientOriginalName();
                $nota_pembayaran->move('storage/nota_perbaikan', $filename);
                $dataInput['nota_perbaikan'] = $filename;
            } else {
                return redirect()->back()->with('error', 'Nota Perbaikan Gagal Diupload.');
            }

            $modelTagihan->create($dataInput);
            $idTagihan = $modelTagihan->latest()->first()->id;
            // dd($idTagihan);

            $this->sendEmailPenagihan($idTagihan);

            return redirect()->route('laporan_kerusakan.index')->with('success', 'Tagihan berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // public function webhooks(Request $request)
    // {
    //     $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //         'Authorization' => "Basic $auth",
    //     ])->get("https://api.sandbox.midtrans.com/v2/$request->order_id/status");


    //     $response = json_decode($response->body());

    //     $tagihan = TagihanKerusakan::where('id', $request->order_id)->first();

    //     if ($tagihan->status == 'settlement' || $tagihan->status == 'capture') {
    //         return response()->json('Payment has been already processed');
    //     }

    //     if ($response->transaction_status == 'capture') {
    //         $tagihan->status = 'capture';
    //     } elseif ($response->transaction_status == 'settlement') {
    //         $tagihan->status = 'settlement';
    //     } elseif ($response->transaction_status == 'pending') {
    //         $tagihan->status = 'pending';
    //     } elseif ($response->transaction_status == 'deny') {
    //         $tagihan->status = 'deny';
    //     } elseif ($response->transaction_status == 'cancel') {
    //         $tagihan->status = 'cancel';
    //     } elseif ($response->transaction_status == 'expire') {
    //         $tagihan->status = 'expire';
    //     } elseif ($response->transaction_status == 'refund') {
    //         $tagihan->status = 'refund';
    //     } else {
    //         $tagihan->status = 'error';
    //     }
    //     $tagihan->save();

    //     return response()->json(['status' => 'success']);
    // }

    public function sendEmailPenagihan($id)
    {
        $tagihanModel = new TagihanKerusakan();
        $tagihan = $tagihanModel->getTagihanKerusakanById($id);
        $data = [
            'name' => $tagihan->laporan_kerusakan->detailPeminjaman->peminjaman->user->name,
            'email' => $tagihan->laporan_kerusakan->detailPeminjaman->peminjaman->user->email,
            'nama_barang' => $tagihan->laporan_kerusakan->detailPeminjaman->barang->nama_barang,
            'total_tagihan' => $tagihan->total_tagihan,
            'deskripsi_kerusakan' => $tagihan->laporan_kerusakan->deskripsi_kerusakan,
            'foto' => $tagihan->laporan_kerusakan->foto_kerusakan[0]->foto,
            'nota_perbaikan' => $tagihan->nota_perbaikan,
            'id_peminjaman' => $tagihan->laporan_kerusakan->detailPeminjaman->peminjaman->id_peminjaman
        ];
        // dd($data);
        Mail::to($data['email'])->send(new TagihanPenggantian($data));

        return response()->json(['status' => 'Email sent successfully']);
    }

    public function getLaporanKerusakan()
    {
        $laporan_kerusakan = $this->ModelLaporan->getBarangKategori();
        return response()->json($laporan_kerusakan);
    }

    public function unduhLaporan()
    {
        return view('laporan.kerusakan.unduh_laporan');
    }

    public function downloadLaporanKerusakan(Request $request)
    {
        $jangka_waktu = $request->jangka_waktu;

        if ($jangka_waktu == '1') {
            $waktu = $request->tahun;
            $laporan_kerusakan = $this->ModelLaporan->laporanKerusakanByTahun($waktu);
        } elseif ($jangka_waktu == '2') {
            $waktu = $request->bulan;
            $laporan_kerusakan = $this->ModelLaporan->laporanKerusakanByBulan($waktu);
        } else {
            $laporan_kerusakan = $this->ModelLaporan->getBarangKategori();
        }
        
        if (!$laporan_kerusakan) {
            return redirect()->back()->with('error', 'Data laporan kerusakan tidak ditemukan.');
        }
        $this->laporanExcel($laporan_kerusakan);

        // Tentukan nama file berdasarkan filter waktu
        $filename = 'Laporan Kerusakan dan Kehilangan.xlsx';
        if ($request->has('jangka_waktu')) {
            $jangka_waktu = $request->get('jangka_waktu');
            if ($jangka_waktu == '1') {
            $tahun = date('Y');
            $filename = 'Laporan Kerusakan dan Kehilangan ' . $tahun . '.xlsx';
            } elseif ($jangka_waktu == '2' && $request->has('bulan')) {
            $bulan = $request->get('bulan');
            $tahun = date('Y');
            $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10));
            $filename = 'Laporan Kerusakan dan Kehilangan ' . $namaBulan . ' ' . $tahun . '.xlsx';
            }
        }
        return response()->download(storage_path('app/public/' . $filename));
    }

    public function laporanExcel($data)
    {
        // generate excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID Laporan Kerusakan');
        $sheet->setCellValue('B1', 'Nama Peminjam');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Kategori Barang');
        $sheet->setCellValue('E1', 'Deskripsi Kerusakan');
        $sheet->setCellValue('F1', 'Tanggal Laporan');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Biaya Ganti Rugi');
        $sheet->setCellValue('I1', 'Status Pembayaran');

        // Set header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF808080'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Set cell border style
        $cellStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $column = 2;
        foreach ($data as $laporan) {
            $sheet->setCellValue('A' . $column, $laporan->id_laporan_kerusakan);
            $sheet->setCellValue('B' . $column, $laporan->nama_peminjam);
            $sheet->setCellValue('C' . $column, $laporan->nama_barang);
            $sheet->setCellValue('D' . $column, $laporan->kategori_barang);
            $sheet->setCellValue('E' . $column, $laporan->deskripsi_kerusakan);
            $sheet->setCellValue('F' . $column, $laporan->tanggal_laporan);
            if ($laporan->status_barang == 'Baik') {
                $sheet->setCellValue('G' . $column, 'Telah Diperbaiki');
            } elseif ($laporan->status_barang == 'Dalam Perbaikan') {
                $sheet->setCellValue('G' . $column, 'Dalam Perbaikan');
            } else {
                $sheet->setCellValue('G' . $column, 'Belum Diperbaiki');
            }
            if (!empty($laporan->biaya_ganti_rugi)) {
                $sheet->setCellValue('H' . $column, 'Rp ' . number_format($laporan->biaya_ganti_rugi, 0, ',', '.'));
            } else {
                $sheet->setCellValue('H' . $column, 'Belum Ditentukan');
            }
            if ($laporan->status_pembayaran == 'capture' || $laporan->status_pembayaran == 'settlement') {
                $laporan->status_pembayaran = 'Lunas';
            } else {
                $laporan->status_pembayaran = 'Belum Lunas';
            }
            $sheet->setCellValue('I' . $column, $laporan->status_pembayaran);
            $sheet->getStyle('A' . $column . ':I' . $column)->applyFromArray($cellStyle);
            $column++;
        }

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // Tentukan nama file berdasarkan filter waktu
        $filename = 'Laporan Kerusakan dan Kehilangan.xlsx';
        if (request()->has('jangka_waktu')) {
            $jangka_waktu = request()->get('jangka_waktu');
            if ($jangka_waktu == '1') {
            $tahun = date('Y');
            $filename = 'Laporan Kerusakan dan Kehilangan ' . $tahun . '.xlsx';
            } elseif ($jangka_waktu == '2' && request()->has('bulan')) {
            $bulan = request()->get('bulan');
            $tahun = date('Y');
            $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10));
            $filename = 'Laporan Kerusakan dan Kehilangan ' . $namaBulan . ' ' . $tahun . '.xlsx';
            }
        }
        $path = storage_path('app/public/' . $filename);
        $writer->save($path);
    }
}
