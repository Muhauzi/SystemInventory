<x-layout title="Edit User">
    <x-slot name="style">
        <style>
            .card {
                border-radius: 10px;
            }
        </style>
    </x-slot>
    <div class="pagetitle">
        <h1>Edit User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-6"> <!-- Mengubah ukuran kolom menjadi sedang -->
                <div class="card">
                    <div class="card-header">
                        Form Edit User
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Form Edit User</h5> <!-- Judul Form yang diubah -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form action="{{ route('kelola_user.update', $user->id) }}" method="post" enctype="multipart/form-data" class="form"> <!-- Menambahkan enctype="multipart" pada form -->
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <script>
                                document.getElementById('togglePassword').addEventListener('click', function () {
                                    const passwordField = document.getElementById('password');
                                    const passwordIcon = document.getElementById('togglePasswordIcon');
                                    if (passwordField.type === 'password') {
                                        passwordField.type = 'text';
                                        passwordIcon.classList.remove('bi-eye-slash');
                                        passwordIcon.classList.add('bi-eye');
                                    } else {
                                        passwordField.type = 'password';
                                        passwordIcon.classList.remove('bi-eye');
                                        passwordIcon.classList.add('bi-eye-slash');
                                    }
                                });
                            </script>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="pimpinan" {{ $user->role == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                    <option value="partnership" {{ $user->role == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                </select>
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