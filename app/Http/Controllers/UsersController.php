<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\TagihanKerusakan;
use App\Models\detailPeminjaman;
use App\Models\Inventaris;
use App\Models\DetailUsers;

class UsersController extends Controller
{
    public function index() // Menampilkan data user
    {
        return view('user.index', compact('detail')); // Menampilkan data user
    }

    public function show()  // Menampilkan profile user
    {
        $profile = User::find(Auth::user()->id);
        $detail = DetailUsers::where('user_id', Auth::user()->id)->get()->first();

        return view('user.profile', compact('profile', 'detail')); // Menampilkan profile user
    }

    public function updateProfile(Request $request) // Mengubah data profile user
    {

        $user = User::find(Auth::user()->id);   // Mengambil data user berdasarkan id
        $user->name = $request->name;   // Menambahkan data nama dari form
        $user->email = $request->email; // Menambahkan data email dari form


        $detail = DetailUsers::where('user_id', Auth::user()->id)->first(); // Mengambil data detail user berdasarkan id user

        $foto = $request->file('foto'); // Menambahkan data foto dari form
        if ($request->hasFile('foto')) {    // Jika ada file foto
            $foto->move('profileImages', $foto->getClientOriginalName());   // Pindahkan foto ke folder profileImages
            $namaFoto = $foto->getClientOriginalName();  // Menambahkan data profile_image dari form
        } else {
            $namaFoto = $detail->profile_image; // Jika tidak ada file foto, gunakan foto lama
        }

        $data = [
            'phone' => $request->phone,
            'department' => $request->department,
            'address' => $request->address,
            'about' => $request->about,
            'profile_image' => $namaFoto,
        ];   // Menyimpan data detail user

        if ($detail) {
            $detail->update($data); // Mengupdate data detail user
        } else {
            DetailUsers::create(array_merge($data, ['user_id' => Auth::user()->id])); // Membuat data detail user baru jika belum ada
        }

        $user->save();  // Menyimpan data user

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword(Request $request)    // Mengubah password user
    {
        $request->validate(
            [
                'current_password' => 'required',
                'new_password' => 'required|min:8',
            ],
            [
                'current_password.required' => 'Password lama harus diisi.',
                'new_password.required' => 'Password baru harus diisi.',
                'new_password.min' => 'Password minimal 8 karakter.',
            ]
        ); // Validasi inputan

        $user = User::find(Auth::user()->id);   // Mengambil data user berdasarkan id

        if (password_verify($request->current_password, $user->password)) {   // Jika password lama sesuai
            if ($request->current_password == $request->new_password) {    // Jika password lama sama dengan password baru
                return redirect()->back()->with('error', 'Password baru tidak boleh sama dengan password lama.');
            } elseif ($request->new_password != $request->confirm_password) {    // Jika password baru tidak sama dengan konfirmasi password baru
                return redirect()->back()->with('error', 'Konfirmasi password baru tidak sesuai.');
            } else {
                $user->password = bcrypt($request->new_password);  // Menambahkan data password baru
            }
            $user->save();  // Menyimpan data user

            return redirect()->back()->with('success', 'Password berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Password lama salah.');
    }

    public function riwayatPeminjaman()   // Menampilkan riwayat peminjaman user
    {
        $peminjaman = Peminjaman::where('status', 'Dikembalikan')->where('id_user', Auth::user()->id)->get(); // Mengambil data peminjaman berdasarkan status dan id user
        return view('user.riwayatPeminjaman', compact('peminjaman'));
    }

    public function detailPeminjaman($id)   // Menampilkan detail peminjaman user
    {
        $peminjaman = Peminjaman::find($id);    // Mengambil data peminjaman berdasarkan id
        $detailPeminjaman = DetailPeminjaman::where('id_peminjaman', $id)->get();   // Mengambil data detail peminjaman berdasarkan id peminjaman
        $barang = Inventaris::whereIn('id_barang', $detailPeminjaman->pluck('id_barang'))->get();   // Mengambil data barang berdasarkan id barang
        $users = User::where('id', $peminjaman->id_user)->get()->first();

        if (!$peminjaman) {
            return redirect()->back()
                ->with('error', 'Data peminjaman tidak ditemukan.');
        }

        return view('user.detailPeminjaman', compact('peminjaman', 'detailPeminjaman', 'barang', 'users'));
    }

    public function listTagihanKerusakan()
    {
        $modelTagihan = new TagihanKerusakan();
        $id = Auth::user()->id;
        $tagihan = $modelTagihan->getTagihanKerusakanByUserId($id);
        // dd($tagihan);

        return view('user.tagihanKerusakan', compact('tagihan'));
    }
}
