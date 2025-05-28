<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBarang;

class KategoriController extends Controller
{
    public function index() // Menampilkan data kategori 
    {
        $kategori = KategoriBarang::all(); // Mengambil semua data kategori
        return view('kategori.index', compact('kategori')); // Menampilkan data kategori
    }

    public function create() // Menampilkan form tambah kategori
    {
        return view('kategori.create'); // Menampilkan form tambah kategori
    }

    public function store(Request $request) // Menyimpan data kategori
    {
        $request->validate([ // Validasi inputan
            'nama_kategori' => 'required',
            'kode_kategori' => 'required|unique:kategori_barang,kode_kategori',
        ]);
        $request->merge(['nama_kategori' => ucwords($request->nama_kategori)]); // Mengubah huruf pertama menjadi kapital
        $request->merge(['kode_kategori' => strtoupper($request->kode_kategori)]); // Mengubah huruf menjadi kapital
 
        KategoriBarang::create($request->all()); // Menyimpan data kategori
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id) // Menampilkan form edit kategori
    {
        $kategori = KategoriBarang::find($id); // Mengambil data kategori berdasarkan id
        return view('kategori.edit', compact('kategori')); // Menampilkan form edit kategori
    } 

    public function update(Request $request, $id) // Mengubah data kategori
    {
        $request->validate([ // Validasi inputan
            'nama_kategori' => 'required'
        ]); 

        KategoriBarang::find($id)->update($request->all()); // Mengubah data kategori
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diubah'); 
    }

    public function destroy($id) // Menghapus data kategori
    {
        $m_kategori = new KategoriBarang();
        $isUsed = $m_kategori->isUsed($id); // Mengecek apakah kategori digunakan
        // dd($id);
        // dd($isUsed);
        if ($isUsed) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak dapat dihapus karena sedang digunakan');
        }
        $kategori = $m_kategori->getKategoriById($id); // Mengambil data kategori berdasarkan id
        if (!$kategori) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak ditemukan');
        }
        $kategori->delete(); // Menghapus data kategori
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
