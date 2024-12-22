<x-layout title="Tambah User" >
    <x-slot name="style">
        <style>
            .card {
                border-radius: 10px;
            }
        </style>
    </x-slot>
    <div class="pagetitle">
        <h1>Tambah User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item active">Form Tambah User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
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
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="pimpinan">Pimpinan</option>
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
    
    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            }
        });
    </script>
</x-layout>