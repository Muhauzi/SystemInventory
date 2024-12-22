<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman as ModelPeminjaman;
use App\Models\Inventaris;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\User;
use App\Models\DetailPeminjaman as ModelDetailPeminjaman;
use App\Models\BatasPeminjaman;

class PeminjamanController extends Controller
{

    public function index() // Menampilkan data peminjaman
    {
        $peminjaman = ModelPeminjaman::where('status', 'Dipinjam')->get(); // Mengambil semua data peminjaman
        $users = User::all();   // Mengambil semua data user

        return view('peminjaman.index', compact('peminjaman', 'users'));    // Menampilkan data peminjaman
    }

    public function laporan() // Menampilkan data peminjaman
    {
        $peminjaman = ModelPeminjaman::all(); // Mengambil semua data peminjaman
        $users = User::all();   // Mengambil semua data user

        return view('peminjaman.index', compact('peminjaman', 'users'));    // Menampilkan data peminjaman
    }



    public function pengembalian() // Menampilkan data pengembalian
    {
        $peminjaman = ModelPeminjaman::where('status', 'Dikembalikan')->get(); // Mengambil data peminjaman berdasarkan status dikembalikan

        $title = 'Data Pengembalian'; // Menambahkan title

        $users = User::all();   // Mengambil semua data user

        return view('peminjaman.index', compact('peminjaman', 'title', 'users')); // Menampilkan data pengembalian
    }

    public function show($id) // Menampilkan detail peminjaman
    {
        $peminjaman = ModelPeminjaman::find($id); // Mengambil data peminjaman berdasarkan id
        $detailPeminjaman = (new ModelDetailPeminjaman())->getDetail($peminjaman->id_peminjaman); // Mengambil data detail peminjaman berdasarkan id peminjaman
        // dd($detailPeminjaman);
        $users = User::find($peminjaman->id_user); // Mengambil data user berdasarkan id user
        $modelDetail = new ModelDetailPeminjaman();

        if (!$peminjaman) { // Jika data peminjaman tidak ditemukan
            return redirect()->back()
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }

        return view('peminjaman.show', compact('peminjaman', 'users', 'detailPeminjaman', 'modelDetail')); // Menampilkan detail peminjaman
    }

    public function listPerizinan() // Menampilkan data perizinan
    {
        $peminjaman = ModelPeminjaman::whereIn('status', ['Pending', 'Disetujui', 'Ditolak'])->get(); // Mengambil data peminjaman berdasarkan status pending atau disetujui
        $users = User::all();   // Mengambil semua data user
        $batasPeminjaman = BatasPeminjaman::first();

        return view('peminjaman.list-perizinan', compact('peminjaman', 'users', 'batasPeminjaman')); // Menampilkan data perizinan
    }

    public function create() // Menampilkan form tambah peminjaman
    {
        $inventaris = Inventaris::where('status_barang', 'Tersedia')->get(); // Mengambil data inventaris yang status barangnya tersedia
        $users = User::all(); // Mengambil semua data user

        return view('peminjaman.add', compact('inventaris', 'users')); // Menampilkan form tambah peminjaman
    }

    public function store(Request $request) // Menyimpan data peminjaman
    {
        $request->validate([
            'id_user' => 'required',
            'id_barang' => 'required|array',
            'id_barang.*' => 'required',
            'tgl_pinjam' => 'required',
            'tgl_tenggat' => 'required',
            'keterangan' => 'required',
        ], [
            'id_user.required' => 'ID User harus diisi.',
            'id_barang.required' => 'ID Barang harus diisi.',
            'id_barang.*.required' => 'ID Barang harus diisi.',
            'tgl_pinjam.required' => 'Tanggal pinjam harus diisi.',
            'tgl_tenggat.required' => 'Tanggal tenggat harus diisi.',
            'keterangan.required' => 'Keterangan harus diisi.',
        ]); // Validasi inputan

        $id_user = $request->id_user; // Menambahkan data id_user dari form
        $tgl_pinjam = $request->tgl_pinjam;  // Menambahkan data tgl_pinjam dari form
        $tgl_tenggat = $request->tgl_tenggat;   // Menambahkan data tgl_tenggat dari form
        $id_barang_list = $request->id_barang; // Menambahkan data id_barang dari form

        $data = [
            'id_user' => $id_user, // Menambahkan data id_user dari form
            'tgl_pinjam' => $tgl_pinjam, // Menambahkan data tgl_pinjam dari form
            'tgl_tenggat' => $tgl_tenggat, // Menambahkan data tgl_tenggat dari form
            'status' => 'Dipinjam', // Menambahkan data status dari form
            'keterangan' => $request->keterangan, // Menambahkan data keterangan dari form
        ];

        $peminjaman = ModelPeminjaman::create($data); // Menyimpan data peminjaman

        foreach ($id_barang_list as $id_barang) { // Looping data id_barang_list
            ModelDetailPeminjaman::create([ // Menyimpan data detail peminjaman
                'id_peminjaman' => $peminjaman->id_peminjaman, // Menambahkan data id_peminjaman dari form
                'id_barang' => $id_barang, // Menambahkan data id_barang dari form
            ]);
            Inventaris::where('id_barang', $id_barang)->update([ // Mengupdate data inventaris
                'status_barang' => 'Dipinjam' // Menambahkan data status_barang dari form
            ]);
        }

        $detailModel = new ModelDetailPeminjaman(); // Menambahkan data detailModel
        $detailPeminjaman = $detailModel->getDetail($peminjaman->id_peminjaman); // Mengambil data detail peminjaman berdasarkan id peminjaman
        $barang = Inventaris::whereIn('id_barang', $detailPeminjaman->pluck('id_barang'))->get();   // Mengambil data barang berdasarkan id barang
        $nilaiBarangDipinjam = $barang->sum('harga_barang'); // Menjumlahkan nilai barang yang dipinjam

        $batasNominal = BatasPeminjaman::first()->batas_nominal; // Mengambil data batas nominal peminjaman

        if ($nilaiBarangDipinjam >= $batasNominal) { // Jika nilai barang yang dipinjam lebih dari $batasNominal
            $data['status'] = 'Pending'; // Menambahkan data status dari form
            $peminjaman->update($data); // Mengupdate data peminjaman

            return redirect()->route('peminjaman.index')
                ->with('success', 'Data peminjaman berhasil ditambahkan. Menunggu persetujuan atasan.'); // Redirect ke route peminjaman.index
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil ditambahkan.'); // Redirect ke route peminjaman.index
    }

    public function scanReturn(Request $request) // Scan QR Code untuk mencari data peminjaman
    {
        $id_barang = $request->id_barang; // Menambahkan data id_barang dari form hasil scan QR Code

        return redirect()->route('peminjaman.show', $id_barang); // Redirect ke route peminjaman.show
    }


    public function buktiPinjam($id) // Membuat bukti peminjaman 
    {
        $peminjaman = ModelPeminjaman::find($id); // Mengambil data peminjaman berdasarkan id
        $detailPeminjaman = ModelDetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)->get(); // Mengambil data detail peminjaman berdasarkan id peminjaman
        $barang = Inventaris::whereIn('id_barang', $detailPeminjaman->pluck('id_barang'))->get();   // Mengambil data barang berdasarkan id barang
        $nama_peminjam = User::where('id', $peminjaman->id_user)->first()->name;    // Mengambil data user berdasarkan id user


        if (!$peminjaman) { // Jika data peminjaman tidak ditem
            return redirect()->back()
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $qrCodePath = 'qrPeminjaman/' . $peminjaman->id_peminjaman . '.png'; // Menambahkan path qrCode
        $fullPath = storage_path('app/public/' . $qrCodePath); // Menambahkan fullPath

        if (!file_exists(dirname($fullPath))) { // Jika folder tidak ada
            mkdir(dirname($fullPath), 0755, true); // Membuat folder
        }

        QrCode::format('png')->size(200)->generate($peminjaman->id_peminjaman, $fullPath); // Membuat QR Code dengan format png

        $phpWord = new \PhpOffice\PhpWord\PhpWord();  // Menambahkan library PhpWord

        $section = $phpWord->addSection(); // Menambahkan section

        // Tambahkan judul dokumen
        if ($peminjaman->status == 'Dipinjam') { // Jika status peminjaman dipinjam
            $section->addText( // Menambahkan text
                'INVOICE PEMINJAMAN BARANG INVENTARIS',
                ['name' => 'Arial', 'size' => 16, 'bold' => true],
                ['align' => 'center']
            );
        } else {
            $section->addText( // Menambahkan text
                'INVOICE PENGEMBALIAN BARANG INVENTARIS',
                ['name' => 'Arial', 'size' => 16, 'bold' => true],
                ['align' => 'center']
            );
        }

        // Tambahkan jarak setelah judul
        $section->addTextBreak(1);

        // Tambahkan detail peminjaman
        $section->addText("Detail Peminjaman:", ['name' => 'Arial', 'size' => 10, 'bold' => true]);

        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
        $phpWord->addTableStyle('DetailPeminjamanTable', $tableStyle);

        $table = $section->addTable('DetailPeminjamanTable');
        $table->addRow();
        $table->addCell(3000)->addText('Nama Peminjam', ['bold' => true]);
        $table->addCell(6000)->addText($nama_peminjam);

        $table = $section->addTable('DetailPeminjamanTable');
        $table->addRow();
        $table->addCell(3000)->addText('Tanggal Pinjam', ['bold' => true]);
        $table->addCell(6000)->addText($peminjaman->tgl_pinjam);

        $table->addRow();
        $table->addCell(3000)->addText('Tanggal Kembali', ['bold' => true]);
        $table->addCell(6000)->addText($peminjaman->tgl_tenggat);

        $table->addRow();
        $table->addCell(3000)->addText('Keperluan', ['bold' => true]);
        $table->addCell(6000)->addText($peminjaman->keterangan);

        $table->addRow();
        $table->addCell(3000)->addText('Status', ['bold' => true]);
        $table->addCell(6000)->addText($peminjaman->status);

        // Tambahkan tabel barang
        $section->addText("Detail Barang:", ['name' => 'Arial', 'size' => 10, 'bold' => true]);
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
        $phpWord->addTableStyle('BarangTable', $tableStyle);

        $table = $section->addTable('BarangTable');
        $table->addRow();
        $table->addCell(2000)->addText('ID Barang', ['bold' => true]);
        $table->addCell(6000)->addText('Nama Barang', ['bold' => true]);
        $table->addCell(3000)->addText('Keterangan', ['bold' => true]);

        foreach ($barang as $item) {
            $table->addRow();
            $table->addCell(2000)->addText($item->id_barang);
            $table->addCell(6000)->addText($item->nama_barang);
            $table->addCell(3000)->addText($item->deskripsi_barang);
        }

        // $table->addRow();
        // $table->addCell(2000)->addText($barang->id_barang);
        // $table->addCell(6000)->addText($barang->nama_barang);
        // $table->addCell(3000)->addText($barang->deskripsi_barang);

        // Tambahkan QR Code
        $section->addTextBreak(1);
        $section->addText(
            'QR Code Verifikasi',
            ['name' => 'Arial', 'size' => 14, 'bold' => true],
            ['align' => 'center']
        );
        $section->addImage(
            $fullPath,
            [
                'width' => 75,
                'height' => 75,
                'align' => 'center'
            ]
        );

        // Tambahkan catatan
        $section->addTextBreak(1);
        $section->addText('Catatan:', ['name' => 'Arial', 'size' => 10, 'bold' => true]);
        $section->addText(
            '1. Barang yang dipinjam harus dikembalikan sesuai dengan tanggal yang disepakati.',
            ['name' => 'Arial', 'size' => 10]
        );
        $section->addText(
            '2. Jika barang rusak atau hilang, peminjam bertanggung jawab atas biaya penggantian.',
            ['name' => 'Arial', 'size' => 10]
        );

        // Tambahkan ruang tanda tangan
        $section->addTextBreak(1);

        $table = $section->addTable();
        $table->addRow();

        // Penanggung Jawab
        $cell1 = $table->addCell(6000);
        $cell1->addText("PJ Inventaris,", ['name' => 'Arial', 'size' => 10]);
        $cell1->addTextBreak(3); // Space for signature
        $cell1->addText("__________________", ['name' => 'Arial', 'size' => 10]);

        $cell2 = $table->addCell(4000);
        $cell2->addTextBreak(3); // Space for signature

        // Peminjam
        $cell3 = $table->addCell(4000);
        $cell3->addText("Peminjam,", ['name' => 'Arial', 'size' => 10]);
        $cell3->addTextBreak(3); // Space for signature
        $cell3->addText("__________________", ['name' => 'Arial', 'size' => 10]);


        if ($peminjaman->status == 'Dipinjam') { // Jika status peminjaman dipinjam
            $filename = 'InvoicePeminjaman_' . $peminjaman->id_peminjaman . '.docx';
            $path = storage_path('app/public/bukti_peminjaman/' . $filename);
        } else {
            $filename = 'InvoicePengembalian_' . $peminjaman->id_peminjaman . '.docx';
            $path = storage_path('app/public/bukti_pengembalian/' . $filename);
        }

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($path);

        return response()->download($path);
    }
    public function edit($id) // Menampilkan form edit peminjaman
    {
        $peminjaman = ModelPeminjaman::find($id); // Mengambil data peminjaman berdasarkan id
        $detailPeminjaman = ModelDetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)->get();    // Mengambil data detail peminjaman berdasarkan id peminjaman
        $barang = Inventaris::whereIn('id_barang', $detailPeminjaman->pluck('id_barang'))->get();   // Mengambil data barang berdasarkan id barang
        $users = User::where('id', $peminjaman->id_user)->first();  // Mengambil data user berdasarkan id user

        return view('peminjaman.edit', compact('peminjaman', 'barang', 'users'));   // Menampilkan form edit peminjaman
    }

    public function update(Request $request, $id)   // Mengubah data peminjaman
    {
        $peminjaman = ModelPeminjaman::find($id);   // Mengambil data peminjaman berdasarkan id
        if (!$peminjaman) { // Jika data peminjaman tidak ditem
            return redirect()->back()
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $status = $request->status; // Menambahkan data status dari form
        if ($status == 'Dikembalikan') {    // Jika status peminjaman dikembalikan
            $peminjaman->status = $status;  // Menambahkan data status dari form
            $peminjaman->tgl_kembali = date('Y-m-d');   // Menambahkan data tgl_kembali dari form
            $peminjaman->save();    // Menyimpan data peminjaman

            $detailPeminjaman = ModelDetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)->get();   // Mengambil data detail peminjaman berdasarkan id peminjaman
            $barang = Inventaris::whereIn('id_barang', $detailPeminjaman->pluck('id_barang'))->get();   // Mengambil data barang berdasarkan id barang

            $kondisi_barang = $request->kondisi;    // Menambahkan data kondisi dari form
            foreach ($barang as $brg) { // Looping data barang
                if (isset($kondisi_barang[$brg->id_barang])) {  // Jika kondisi barang ada
                    $brg->kondisi = $kondisi_barang[$brg->id_barang];   // Menambahkan data kondisi dari form
                    if ($brg->kondisi == 'Rusak' || $brg->kondisi == 'Hilang') {   // Jika kondisi barang rusak atau hilang
                        $brg->status_barang = 'Tidak Tersedia'; // Menambahkan data status_barang dari form
                    } else {
                        $brg->status_barang = 'Tersedia';
                    }
                    $brg->save();
                }
            }

            foreach ($barang as $brg) { // Looping data barang
                if (!$brg->save()) {
                    return redirect()->back()
                        ->with('error', 'Data peminjaman gagal diubah.');
                }
            }
            return redirect()->route('peminjaman.index')
                ->with('success', 'Data peminjaman berhasil diubah.');
        } elseif ($status == 'Dipinjam') { // Jika status peminjaman disetujui
            $peminjaman->status = $status;  // Menambahkan data status dari form
            $peminjaman->save();    // Menyimpan data peminjaman

            return redirect()->route('peminjaman.listPerizinan')
                ->with('success', 'Data peminjaman berhasil diubah.');
        } else {
            return redirect()->back()
                ->with('error', 'Status peminjaman tidak valid.');
        }
    }

    public function logPeminjaman() // Menampilkan log peminjaman user
    {
        $status = 'Dipinjam';  // Menambahkan data status dari form
        $peminjaman = ModelPeminjaman::where('status', $status)->get(); // Mengambil data peminjaman berdasarkan status

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function unduhLaporan()
    {
        return view('laporan.transaksi.unduh_laporan');
    }

    public function unduhLaporanPeminjaman(Request $request)
    {
        $jangka_waktu = $request->jangka_waktu;

        $ModelPeminjaman = new ModelPeminjaman();

        if ($jangka_waktu == '1') {
            $waktu = $request->tahun;
            $laporan_peminjaman = $ModelPeminjaman->laporanPeminjamanByTahun($waktu);
        } elseif ($jangka_waktu == '2') {
            $waktu = $request->bulan;
            $laporan_peminjaman = $ModelPeminjaman->laporanPeminjamanByBulan($waktu);
        } else {
            $laporan_peminjaman = $ModelPeminjaman->getBarangKategori();
        }

        if ($laporan_peminjaman == false) {
            return redirect()->back()
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // dd($laporan_peminjaman);

        $path = $this->laporanPeminjamanExcel($laporan_peminjaman, $jangka_waktu, $waktu);
        session()->flash('success', 'Laporan berhasil diunduh');
        return response()->download($path);
    }

    public function laporanPeminjamanExcel($data, $when, $waktu)
    {
        // Membuat objek spreadsheet baru
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Mengatur sheet aktif ke sheet pertama (index 0)
        $excel->setActiveSheetIndex(0);

        // Mendapatkan sheet aktif untuk digunakan
        $sheet = $excel->getActiveSheet();

        // Menentukan judul laporan berdasarkan parameter 'when'
        if ($when == '1') {
            // Laporan tahunan
            $sheet->setCellValue('A1', 'Laporan Peminjaman Tahun ' . $waktu);
        } elseif ($when == '2') {
            // Laporan bulanan
            $months = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
            $sheet->setCellValue('A1', 'Laporan Peminjaman Bulan ' . $months[$waktu]);
        } else {
            // Judul umum jika parameter tidak sesuai
            $sheet->setCellValue('A1', 'Laporan Peminjaman');
        }

        // Menggabungkan sel untuk judul agar terlihat di tengah (dari kolom A sampai N)
        $sheet->mergeCells('A1:N1');
        // Membuat teks judul tebal (bold)
        $sheet->getStyle('A1')->getFont()->setBold(true);
        // Menyetel teks judul agar rata tengah secara horizontal
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Menambahkan header kolom untuk tabel laporan
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Tanggal Peminjaman');
        $sheet->setCellValue('C3', 'Nama Peminjam');
        $sheet->setCellValue('D3', 'ID Peminjam');
        $sheet->setCellValue('E3', 'Nama Barang');
        $sheet->setCellValue('F3', 'Kategori Barang');
        $sheet->setCellValue('G3', 'Kode Barang');
        $sheet->setCellValue('H3', 'Nominal Barang');
        $sheet->setCellValue('I3', 'Persetujuan Pimpinan');
        $sheet->setCellValue('J3', 'Tanggal Pengembalian');
        $sheet->setCellValue('K3', 'Kondisi Barang');

        // Menentukan gaya untuk header tabel (warna, font, rata tengah)
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '4F81BD']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        // Menerapkan gaya header pada baris ke-3 (header tabel)
        $sheet->getStyle('A3:N3')->applyFromArray($headerStyle);

        // Mengatur kolom agar lebarnya menyesuaikan isi
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Inisialisasi nomor urut dan baris awal untuk data
        $no = 1;
        $row = 4;

        // Mengisi data ke dalam tabel dari parameter $data
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no); // Nomor urut
            $sheet->setCellValue('B' . $row, $item->tgl_pinjam); // Tanggal peminjaman
            $sheet->setCellValue('C' . $row, $item->name); // Nama peminjam
            $sheet->setCellValue('D' . $row, $item->id_user); // ID peminjam
            $sheet->setCellValue('E' . $row, $item->nama_barang); // Nama barang
            $sheet->setCellValue('F' . $row, $item->nama_kategori); // Kategori barang
            $sheet->setCellValue('G' . $row, $item->id_barang); // Kode barang
            $sheet->setCellValue('H' . $row, $item->harga_barang); // Harga barang
            $sheet->setCellValue('I' . $row, $item->status); // Status persetujuan
            // Tanggal pengembalian (jika ada), jika tidak ada ditampilkan '-'
            if ($item->tgl_kembali != null) {
                $sheet->setCellValue('J' . $row, $item->tgl_kembali);
            } else {
                $sheet->setCellValue('J' . $row, '-');
            }
            $sheet->setCellValue('K' . $row, $item->kondisi); // Kondisi barang

            $no++; // Menambah nomor urut
            $row++; // Pindah ke baris berikutnya
        }

        // Membuat writer untuk menyimpan file Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);

        // Menentukan nama file berdasarkan parameter 'when'
        if ($when == '1') {
            // Nama file untuk laporan tahunan
            $filename = 'laporan_peminjaman_' . $waktu;
        } elseif ($when == '2') {
            // Nama file untuk laporan bulanan
            $months = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
            $filename = 'laporan_transaksi_' . $months[$waktu];
        } else {
            $sheet->setCellValue('A1', 'Laporan Peminjaman');
        }

        // Menentukan path tempat penyimpanan file
        $path = storage_path('app/public/laporan_transaksi/' . $filename . '.xlsx');

        // Membuat folder jika belum ada
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        // Menyimpan file Excel di path yang sudah ditentukan
        $writer->save($path);

        // Mengembalikan path file yang telah disimpan
        return $path;
    }

    public function updateBatasPeminjaman($id)
    {
        $batasPeminjaman = BatasPeminjaman::find($id); // Mengambil data batas peminjaman berdasarkan
        if (!$batasPeminjaman) { // Jika data batas peminjaman tidak ditemukan
            return redirect()->back()
                ->with('error', 'Data batas peminjaman tidak ditemukan.');
        }
        
        $batasPeminjaman->update(['batas_nominal' => request('batas_nominal')]); // Mengupdate data batas peminjaman

        return redirect()->back()
            ->with('success', 'Data batas peminjaman berhasil diubah.'); // Redirect ke route peminjaman.index
    }
}
