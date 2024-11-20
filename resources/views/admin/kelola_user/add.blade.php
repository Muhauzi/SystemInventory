<x-layout title="Tambah User" >
    <x-slot name="style">
        <style>
            .card {
                border-radius: 10px;
            }
        </style>
    </x-slot>
    <section class="section">
        <div class="row">
            <div class="col-lg-6"> <!-- Mengubah ukuran kolom menjadi sedang -->
                <div class="card">
                    <div class="card-header">
                        Form Tambah User
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Form Tambah User</h5> <!-- Judul Form yang diubah -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('kelola_user.store') }}" method="post" enctype="multipart/form-data" class="form"> <!-- Menambahkan enctype="multipart" pada form -->
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="foto_profil" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control" id="foto_profil" name="profile_image" accept="image/*" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{route('kelola_user.index')}}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form> <!-- Vertical Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>