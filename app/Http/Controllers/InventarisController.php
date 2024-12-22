<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Storage;
use App\Models\DetailPeminjaman;

class InventarisController extends Controller
{
    protected $inventaris;

    public function index()
    {
        $kategori = KategoriBarang::all(); // Mengambil data kategori barang
        $inventaris = Inventaris::all(); // Mengambil data inventaris

        return view('inventaris.index', compact('inventaris', 'kategori')); // Menampilkan halaman index inventaris
    }

    public function barangByKategori($id)
    {
        $inventaris = Inventaris::where('id_kategori', $id)->get(); // Mengambil data inventaris berdasarkan kategori
        $kategori = KategoriBarang::all(); // Mengambil data kategori barang

        $modelDetailPeminjaman = new DetailPeminjaman();
        $isBorrowed = $modelDetailPeminjaman->isBorrowed();


        return view('inventaris.list', compact('inventaris', 'kategori', 'isBorrowed')); // Menampilkan halaman list inventaris
    }

    public function scanQR(Request $request) // untuk scan qr mencari barang melalui qr
    {
        $id_barang = $request->id_barang; // Mengambil id barang dari form

        $inventaris = Inventaris::where('id_barang', $id_barang)->first(); // Mengambil data inventaris berdasarkan id barang

        if (!$inventaris) { // Jika data inventaris tidak ditemukan
            return redirect()->route('inventaris.list')
                ->with('error', 'Barang Inventaris Tidak Ditemukan.');
        }

        return redirect()->route('inventaris.show', $inventaris->id_barang); // Redirect ke halaman detail inventaris
    }

    public function show($id)
    {
        $inventaris = Inventaris::find($id); // Mengambil data inventaris berdasarkan id
        $kategori = KategoriBarang::all(); // Mengambil data kategori barang

        $qrCode = Storage::exists('public/qrcodes/' . $inventaris->qr_code); // Mengecek apakah qrcode ada atau tidak

        if (!$qrCode) { // Jika qrcode tidak ada
            $qrCodePath = 'qrcodes/' . $inventaris->id_barang . '.png'; // Path untuk menyimpan qrcode
            $fullPath = storage_path('app/public/' . $qrCodePath); // Full path untuk menyimpan qrcode

            if (!file_exists(dirname($fullPath))) { // Membuat folder jika tidak ada
                mkdir(dirname($fullPath), 0755, true); // Membuat folder
            }

            QrCode::format('png')->size(200)->generate($inventaris->id_barang, $fullPath); // Membuat qrcode dengan format png

            $fileQr = basename($qrCodePath); // Mengambil nama file qrcode

            $inventaris->update([
                'qr_code' => $fileQr, // Update qr_code pada database
            ]);
        }



        return view('inventaris.show', compact('inventaris', 'kategori')); // Menampilkan halaman detail inventaris
    }

    public function create()
    {
        $kategori = KategoriBarang::all(); // Mengambil data kategori barang
        return view('inventaris.create', compact('kategori')); // Menampilkan halaman form tambah inventaris
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'jumlah_barang' => 'required',
            'foto_barang' => 'required|image',
            'id_kategori' => 'required',
            'harga_barang' => 'required',
            'tgl_pembelian' => 'required',
            'deskripsi_barang' => 'required',
        ], [
            'nama_barang.required' => 'Nama barang harus diisi.',
            'jumlah_barang.required' => 'Jumlah barang harus diisi.',
            'foto_barang.required' => 'Gambar barang harus diisi.',
            'foto_barang.image' => 'File yang diupload harus berupa gambar.',
            'id_kategori.required' => 'Kategori barang harus diisi.',
            'harga_barang.required' => 'Harga satuan barang harus diisi.',
            'tgl_pembelian.required' => 'Tanggal pembelian barang harus diisi.',
            'deskripsi_barang.required' => 'Deskripsi barang harus diisi.',
        ]); // Validasi form


        $gambar = $request->file('foto_barang')->storePublicly('inventaris', 'public'); // Menyimpan gambar ke storage
        if (!$gambar) { // Jika gagal menyimpan gambar
            return redirect()->back()
                ->with('error', 'Gambar barang gagal diupload.');
        }
        $namaGambar = basename($gambar);

        $data = [
            'nama_barang' => $request->nama_barang,
            'status_barang' => 'Tersedia',
            'kondisi' => 'Baik',
            'foto_barang' => $namaGambar,
            'harga_barang' => $request->harga_barang,
            'tgl_pembelian' => $request->tgl_pembelian,
            'id_kategori' => $request->id_kategori,
            'deskripsi_barang' => $request->deskripsi_barang,
        ]; // Data yang akan dimasukkan ke database dari form

        if ($request->jumlah_barang > 1) { //pengulangan untuk membuat barang sebanyak jumlah_barang
            for ($i = 0; $i < $request->jumlah_barang; $i++) {
                $data['id_barang'] = rand(1000, 9999); // Membuat id secara acak agar unique
                
                if (Inventaris::where('id_barang', $data['id_barang'])->exists()) {
                    $data['id_barang'] = rand(1000, 9999); // mengacak ulang jika id sudah ada
                }

                $inv = Inventaris::create($data); // Memasukkan data ke database
                $qrCodePath = 'qrcodes/' . $inv->id_barang . '.png'; // Path untuk menyimpan qrcode
                $fullPath = storage_path('app/public/' . $qrCodePath); // Full path untuk menyimpan qrcode

                if (!file_exists(dirname($fullPath))) { // Membuat folder jika tidak ada
                    mkdir(dirname($fullPath), 0755, true); // Membuat folder
                }

                QrCode::format('png')->size(200)->generate($inv->id_barang, $fullPath); // Membuat qrcode dengan format png 

                $fileQr = basename($qrCodePath); // Mengambil nama file qrcode

                $inv->update([
                    'qr_code' => $fileQr, // Update qr_code pada database
                ]);
            }
        } else { // Jika jumlah barang hanya 1
            $inv = Inventaris::create($data); // Memasukkan data ke database

            $qrCodePath = 'qrcodes/' . $inv->id_barang . '.png'; // Path untuk menyimpan qrcode
            $fullPath = storage_path('app/public/' . $qrCodePath); // Full path untuk menyimpan qrcode

            if (!file_exists(dirname($fullPath))) { // Memeriksa apakah folder sudah ada, jika tidak maka membuat folder
                mkdir(dirname($fullPath), 0755, true); // Membuat folder
            }

            QrCode::format('png')->size(200)->generate($inv->id_barang, $fullPath); // Membuat qrcode dengan format png

            $fileQr = basename($qrCodePath); // Mengambil nama file qrcode

            $inv->update([
                'qr_code' => $fileQr, // Update qr_code pada database
            ]);
        }

        return redirect()->route('inventaris.list', $request->id_kategori)
            ->with('success', 'Barang Inventaris Berhasil Ditambahkan.'); // Redirect ke halaman list barang
    }

    public function qrCreate($id) // Fungsi untuk membuat qrcode jika belum ada
    {
        $inv = Inventaris::find($id); // Mengambil data inventaris berdasarkan id
        $namaBarang = $inv->nama_barang; // Mengambil nama barang
        $qrCodePath = 'qrcodes/' . $inv->id_barang . '.png'; // Path untuk menyimpan qrcode
        $fullPath = storage_path('app/public/' . $qrCodePath); // Full path untuk menyimpan qrcode

        if (!file_exists(dirname($fullPath))) { // Membuat folder jika tidak ada
            mkdir(dirname($fullPath), 0755, true);
        }

        QrCode::format('png')->size(200)->generate($inv->id_barang, $fullPath); // Membuat qrcode dengan format png

        $fileQr = basename($qrCodePath); // Mengambil nama file qrcode

        $inv->update([
            'qr_code' => $fileQr, // Update qr_code pada database
        ]);

        return redirect()->route('inventaris.list')
            ->with('success', 'QRCode Untuk ' . $namaBarang . ' Berhasil Dibuat.'); // Redirect ke halaman list barang
    }

    public function edit($id) // Fungsi untuk menampilkan form edit barang
    {
        $inventaris = Inventaris::find($id); // Mengambil data inventaris berdasarkan id
        $kategori = KategoriBarang::all(); // Mengambil data kategori barang

        return view('inventaris.edit', compact('inventaris', 'kategori')); // Menampilkan halaman form edit barang
    }

    public function update(Request $request, $id) // Fungsi untuk mengupdate data barang
    {
        $inventaris = Inventaris::find($id); // Mengambil data inventaris berdasarkan id

        if (!$inventaris) { // Jika data inventaris tidak ditemukan
            return redirect()->route('inventaris.list')
                ->with('error', 'Barang Inventaris Tidak Ditemukan.'); 
        }

        $request->validate([
            'nama_barang' => 'required',
            'foto_barang' => 'image',
            'id_kategori' => 'required',
            'harga_barang' => 'required',
            'tgl_pembelian' => 'required',
            'deskripsi_barang' => 'required',
        ], [
            'nama_barang.required' => 'Nama barang harus diisi.',
            'foto_barang.image' => 'File yang diupload harus berupa gambar.',
            'id_kategori.required' => 'Kategori barang harus diisi.',
            'harga_barang.required' => 'Harga satuan barang harus diisi.',
            'tgl_pembelian.required' => 'Tanggal pembelian barang harus diisi.',
            'deskripsi_barang.required' => 'Deskripsi barang harus diisi.',
        ]); // Validasi form

        $kondisiBarang = $request->kondisi; // Mengambil kondisi barang
        $statusBarang = $request->status_barang; // Mengambil status barang

        if ($kondisiBarang == 'Rusak' || $kondisiBarang == 'Hilang') { // Jika kondisi barang rusak atau hilang
            if ($statusBarang == 'Dalam Perbaikan') // Jika status barang dalam perbaikan
                $statusBarang = 'Dalam Perbaikan'; // Status barang tetap dalam perbaikan
            else
                $statusBarang = 'Tidak Tersedia'; // Jika tidak dalam perbaikan, maka status barang tidak tersedia
        }

        $data = [
            'nama_barang' => $request->nama_barang,
            'status_barang' => $statusBarang,
            'kondisi' => $request->kondisi,
            'harga_barang' => $request->harga_barang,
            'tgl_pembelian' => $request->tgl_pembelian,
            'id_kategori' => $request->id_kategori,
            'deskripsi_barang' => $request->deskripsi_barang,
        ]; // Data yang akan diupdate ke database dari form

        if ($request->foto_barang) { // Jika ada gambar yang diupload
            $gambar = $request->file('foto_barang')->storePublicly('inventaris', 'public'); // Menyimpan gambar ke storage
            if (!$gambar) { // Jika gagal menyimpan gambar
                return redirect()->back()
                    ->with('error', 'Gambar barang gagal diupload.');
            }

            $namaGambar = basename($gambar); // Mengambil nama gambar

            if ($inventaris->gambar) { // Jika gambar sebelumnya ada
                $path = storage_path('app/public/inventaris/' . $inventaris->gambar); // Path gambar sebelumnya

                if (file_exists($path)) { // Jika file gambar sebelumnya ada
                    unlink($path); // Menghapus gambar sebelumnya
                }
            }

            $data['foto_barang'] = $namaGambar; // Update gambar barang
        } 

        $inventaris->update($data); // Update data inventaris

        return redirect()->route('inventaris.list', $request->id_kategori) // Redirect ke halaman list barang
            ->with('success', 'Barang Inventaris Berhasil Diubah.');
    }

    public function destroy(Request $request, $id) // Fungsi untuk menghapus data barang
    {
        $inventaris = Inventaris::find($id);  // Mengambil data inventaris berdasarkan id

        if (!$inventaris) { // Jika data inventaris tidak ditemukan
            return redirect()->route('inventaris.list')
                ->with('error', 'Barang Inventaris Tidak Ditemukan.');
        }

        if ($inventaris->gambar) { // Jika gambar barang ada
            $path = storage_path('app/public/inventaris/' . $inventaris->gambar); // Path gambar barang

            if (file_exists($path)) { // Jika file gambar ada
                unlink($path); // Menghapus gambar
            }
        }
        $kategori = $request->id_kategori; // Mengambil id kategori barang

        $inventaris->delete(); // Menghapus data inventaris dari database

        return redirect()->route('inventaris.list', $kategori) // Redirect ke halaman list barang
            ->with('success', 'Barang Inventaris Berhasil Dihapus.');
    }


}
