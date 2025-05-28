<x-layout title="List Kategori">
    <div class="pagetitle">
        <h1>List Kategori Barang Inventaris</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kelola Kategori Inventaris</li>
                <li class="breadcrumb-item active">List Kategori Barang Inventaris</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <x-alert></x-alert>
    <x-section>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('kategori.add') }}">
                <button type="button" class="btn btn-primary my-2 btn-icon-text">
                    <i class="ri-add-line"></i> Tambah
                </button>
            </a>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tabel Kategori</h5>
                <table class="table table-borderless datatable table-hover">
                    <thead style="background-color: rgba(233, 239, 248, 0.5);">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Kategori</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategori as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $item->nama_kategori }}</td>
                            <td>
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="{{ route('kategori.edit', $item->id_kategori) }}" class="btn btn-warning btn-sm">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <x-form :action="route('kategori.delete', $item->id_kategori)" :class="' d-inline'">
                                            @method('delete')
                                            <input type="hidden" name="status_pengajuan" value="Ditolak">
                                            <x-slot name="customButton">
                                                <button type="submit" class="btn btn-danger btn-sm">
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

        <script>
            function confirmModal(event) {
                event.preventDefault();
                const form = event.target.form;
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            }
        </script>
    </x-section>
</x-layout>