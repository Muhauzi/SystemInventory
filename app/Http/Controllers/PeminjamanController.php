<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman as ModelPeminjaman;
use App\Models\Inventaris;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\User;
use App\Models\DetailPeminjaman as ModelDetailPeminjaman;
use App\Http\Controllers\LaporanKerusakan;

class PeminjamanController extends Controller
{

    public function index() // Menampilkan data peminjaman
    {
        $peminjaman = ModelPeminjaman::where('status', 'Dipinjam')->get(); // Mengambil semua data peminjaman
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
        $users = User::find($peminjaman->id_user); // Mengambil data user berdasarkan id user

        if (!$peminjaman) { // Jika data peminjaman tidak ditemukan
            return redirect()->back()
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }

        return view('peminjaman.show', compact('peminjaman', 'users', 'detailPeminjaman')); // Menampilkan detail peminjaman
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
}
