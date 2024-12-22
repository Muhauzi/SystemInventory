<x-layout>
    <x-slot name="title">
        Edit Barang Inventaris
    </x-slot>

    <div class="pagetitle">
        <h1>Edit Barang Inventaris</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kelola Inventaris</li>
                <li class="breadcrumb-item active">Edit Barang Inventaris</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="pagetitle" style="display: flex; justify-content: center; align-items: center;">
        <h1 style="font-size: 2.5rem;">Edit Barang Inventaris</h1>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-alert></x-alert>
                        <!-- Multi Columns Form -->
                        <form id="formEdit" class="row g-3 pt-4" method="post"
                            action="{{ route('inventaris.update', $inventaris->id_barang) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="col-md-6">
                                <label for="nama_barang" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                    value="{{ $inventaris->nama_barang }}">
                            </div>
                            <div class="col-md-6">
                                <label for="foto_barang" class="form-label">Foto Barang</label>
                                <input type="file" class="form-control" id="foto_barang" name="foto_barang"
                                    value="{{ $inventaris->foto_barang }}">
                            </div>
                            <div class="col-md-6">
                                <label for="tgl_pembelian" class="form-label">Tanggal Pembelian</label>
                                <input type="date" class="form-control" id="tgl_pembelian" name="tgl_pembelian"
                                    value="{{ $inventaris->tgl_pembelian }}">
                            </div>
                            <div class="col-md-6">
                                <label for="harga_barang" class="form-label">Harga Barang</label>
                                <input type="number" class="form-control" id="harga_barang" name="harga_barang"
                                    value="{{ $inventaris->harga_barang }}">
                            </div>
                            <div class="col-md-6">
                                <label for="id_kategori" class="form-label">Kategori</label>
                                <select id="id_kategori" class="form-select" name="id_kategori">
                                    <option selected>Pilih...</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{ $item->id_kategori }}"
                                            {{ $item->id_kategori == $inventaris->id_kategori ? 'selected' : '' }}>
                                            {{ $item->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status_barang" class="form-label">Status Barang</label>
                                <select id="status_barang" class="form-select" name="status_barang">
                                    <option selected>Pilih...</option>
                                    <option value="Tersedia"
                                        {{ $inventaris->status_barang == 'Tersedia' ? 'selected' : '' }}>Tersedia
                                    </option>
                                    <option value="Dipinjam"
                                        {{ $inventaris->status_barang == 'Dipinjam' ? 'selected' : '' }}>Dipinjam
                                    </option>
                                    <option value="Dalam Perbaikan"
                                        {{ $inventaris->status_barang == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam
                                        Perbaikan</option>
                                    <option value="Tidak Tersedia"
                                        {{ $inventaris->status_barang == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak
                                        Tersedia</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kondisi" class="form-label">Kondisi</label>
                                <select id="kondisi" class="form-select" name="kondisi">
                                    <option selected>Pilih...</option>
                                    <option value="Baik" {{ $inventaris->kondisi == 'Baik' ? 'selected' : '' }}>Baik
                                    </option>
                                    <option value="Rusak" {{ $inventaris->kondisi == 'Rusak' ? 'selected' : '' }}>
                                        Rusak</option>
                                    <option value="Hilang" {{ $inventaris->kondisi == 'Hilang' ? 'selected' : '' }}>
                                        Hilang</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="deskripsi_barang" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi_barang" name="deskripsi_barang" rows="3">{{ $inventaris->deskripsi_barang }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="button" onclick="confirmSubmit()" class="btn btn-primary">Edit</button>
                                <a href="{{ route('inventaris.list', $inventaris->id_kategori) }}">
                                    <button type="button" class="btn btn-secondary my-2 btn-icon-text"> Kembali
                                    </button>
                                </a>
                            </div>

                        </form><!-- End Multi Columns Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ implode(', ', $errors->all()) }}',
                });
            });
        </script>
    @endif

    <script>
        function confirmSubmit() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEdit').submit();
                }
            })
        }
    </script>
</x-layout>
