<x-layout>

    <x-slot name="title">
        Detail Barang
    </x-slot>

    <div class="pagetitle">
        <h1>Detail Barang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">User</li>
                <li class="breadcrumb-item active">Detail Barang</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ back()->getTargetUrl() }}">
            <button type="button" class="btn btn-secondary my-2 btn-icon-text">
                <i class="ri-arrow-go-back-fill"></i> Kembali
            </button>
        </a>
    </div>
    <div class="card">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <h3>Gambar Barang</h3>
                    <div class="frame">
                        <img src="{{ asset('img/inventaris/' . $inventaris['foto_barang']) }}" alt="Gambar" width="400px">
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Detail Barang</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th>Kode Barang</th>
                            <td>{{ $inventaris['id_barang'] }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>{{ $inventaris['nama_barang'] }}</td>
                        </tr>
                        <tr>
                            <th>Harga Barang</th>
                            <td>Rp. {{ number_format($inventaris['harga_barang'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                {{ $inventaris['kategori'] }}
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi Barang</th>
                            <td>
                                {{ $inventaris['deskripsi_barang'] }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-layout>