<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DetailUsers;
use Illuminate\Support\Facades\Auth;

class KelolaUsersController extends Controller
{
    public function index() // Menampilkan data user
    {
        $users = User::all(); // Mengambil semua data user
        return view('admin.kelola_user.index', compact('users')); // Menampilkan data user
    }

    public function create() // Menampilkan form tambah user
    {
        return view('admin.kelola_user.add'); // Menampilkan form tambah user
    }

    public function store(Request $request) // Menyimpan data user
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required',
            'phone' => 'required',
            'department' => 'required',
        ],
        [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role harus diisi.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'department.required' => 'Departemen harus diisi.',
        ]); // Validasi inputan

        $user = new User(); // Menambahkan model User
        $detail = new DetailUsers(); // Menambahkan model DetailUsers
        $user->name = $request->name; // Menambahkan data nama dari form
        $user->email = $request->email; // Menambahkan data email dari form
        $user->password = bcrypt($request->password); // Menambahkan data password dari form
        $user->role = $request->role; // Menambahkan data role dari form
        $user->save(); // Menyimpan data user

        $detail->user_id = $user->id; // Menambahkan data user_id dari user yang baru dibuat
        $detail->phone = $request->phone; // Menambahkan data phone dari form
        $detail->department = $request->department; // Menambahkan data department dari form 
        $detail->save(); // Menyimpan data detail user


        $gambar = $request->file('profile_image'); // Menambahkan data profile_image dari form

        if ($gambar) {  // Jika ada gambar  
            $gambar->move('profileImages', $gambar->getClientOriginalName()); // Pindahkan gambar ke folder profileImages
            $detail->profile_image = $gambar->getClientOriginalName(); // Menambahkan data profile_image dari form
        }

        $detail->save();  // Menyimpan data user

        return redirect()->route('kelola_user.index')->with('success', 'User berhasil ditambahkan'); // Redirect ke route kelola_user.index
    }

    public function edit($id) // Menampilkan form edit user
    {
        $user = User::find($id); // Mengambil data user berdasarkan id
        return view('admin.kelola_user.edit', compact('user')); // Menampilkan form edit user
    }

    public function update(Request $request, $id) // Mengubah data user
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'required|min:8',
        ],
        [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email tidak valid.',
            'role.required' => 'Role harus diisi.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]); // Validasi inputan

        $user = User::find($id); // Mengambil data user dari model user berdasarkan id
        $user->name = $request->name; // Mengubah data nama dari form
        $user->email = $request->email; // Mengubah data email dari form
        $user->role = $request->role;       // Mengubah data role dari form
        
        if ($request->password) { // Jika password diisi
            $user->password = bcrypt($request->password); // Mengubah data password dari form
        } else {
            $user->password = $user->password; // Jika password tidak diisi, gunakan password lama
        }

        $user->save();

        return redirect()->route('kelola_user.index')->with('success', 'User berhasil diubah'); // Redirect ke route kelola_user.index
    }

    public function destroy($id) // Menghapus data user
    {
        $user = User::find($id); // Mengambil data user berdasarkan id
        if ($user->id == Auth::user()->id) { // Jika id user sama dengan id user yang sedang login
            return redirect()->route('kelola_user.index')->with('error', 'Tidak dapat menghapus akun sendiri'); // Redirect ke route kelola_user.index
        }
        $user->delete(); // Menghapus data user 

        return redirect()->route('kelola_user.index')->with('success', 'User berhasil dihapus');    // Redirect ke route kelola_user.index
    }


}
