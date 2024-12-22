<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Inventaris; 
use App\Models\DetailPeminjaman;

class AdminController extends Controller
{
    public function index()
    {
        $modelPeminjaman = new Peminjaman();
        $modelInventaris = new Inventaris();
        $modelUser = new User();

        $dataPeminjaman = $modelPeminjaman->dataPeminjaman();
        $dataInventaris = $modelInventaris->getInventarisKategori();
        $dataUser = $modelUser->all();

        $jumlahBarangByKategori = $dataInventaris->groupBy('nama_kategori')->map(function ($item) {
            return $item->count();
        }); 
        $modelDetailPeminjaman = new DetailPeminjaman();
        $barangDikembalikan = $modelDetailPeminjaman->getBarangDikembalikan(); 
        $barangDipinjam = $modelDetailPeminjaman->getBarangDipinjam();
        // dd($dataUser);

        return view('admin.dashboard', compact('dataPeminjaman', 'dataInventaris', 'dataUser', 'jumlahBarangByKategori', 'barangDikembalikan', 'barangDipinjam'));
    }

    public function kelola_user()
    {
        $users = User::all();

        return view('admin.kelola_user', compact('users'));
    }

    public function create()
    {
        return view('admin.add_user');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'id_pegawai' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'level_akses' => 'required|string|max:50',
        ]);

        User::create($request->all());

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function kelola_inventaris()
    {
        return view('admin.kelola_inventaris');
    }
}
