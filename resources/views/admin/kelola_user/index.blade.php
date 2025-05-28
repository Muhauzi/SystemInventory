<x-layout title="Kelola User">
    <div class="pagetitle">
        <h1>List User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item active">List Users</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('kelola_user.add') }}">
            <button type="button" class="btn btn-primary my-2 btn-icon-text">
                <i class="ri-add-fill"></i> Tambah
            </button>
        </a>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-alert></x-alert>
                        <div class="table-responsive">
                            <h5 class="card-title">Tabel User</h5>
                            <table class="table table-borderless datatable table-hover">
                                <thead style="background-color: rgba(233, 239, 248, 0.5);">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama User</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">{{ $user->id }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-2">
                                                    <a href="{{ route('kelola_user.edit', $user->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                </div>
                                                <div class="col-2">
                                                    <x-form :action="route('kelola_user.delete', $user->id)">
                                                        @method('delete')
                                                        <input type="hidden" name="status_pengajuan" value="Ditolak">
                                                        <x-slot name="customButton">
                                                            <button class="btn btn-danger btn-sm">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </x-slot>
                                                    </x-form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>