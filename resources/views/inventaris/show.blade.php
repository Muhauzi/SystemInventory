<x-layout>

    <x-slot name="title">
        Detail Barang
    </x-slot>

    <div class="pagetitle">
        <h1>Detail Barang Inventaris</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kelola Inventaris</li>
                <li class="breadcrumb-item active">Detail Barang Inventaris</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('inventaris.list' ,  $inventaris->id_kategori )}}">
            <button type="button" class="btn btn-primary my-2 btn-icon-text">
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
                        <img src="{{ Storage::url('inventaris/' . $inventaris->foto_barang)}}" alt="Gambar" width="400px">
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Detail Barang</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th>Kode Barang</th>
                            <td>{{ $inventaris->id_barang }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>{{ $inventaris->nama_barang }}</td>
                        </tr>
                        <tr>
                            <th>Status Barang</th>
                            <td>
                                @if ($inventaris->status_barang == 'Tersedia')
                                <span class="badge bg-success">{{ $inventaris->status_barang }}</span>
                                @elseif ($inventaris->status_barang == 'Dipinjam')
                                <span class="badge bg-info">{{ $inventaris->status_barang }}</span>
                                @elseif ($inventaris->status_barang == 'Dalam Perbaikan')
                                <span class="badge bg-warning">{{ $inventaris->status_barang }}</span>
                                @else
                                <span class="badge bg-danger">{{ $inventaris->status_barang }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kondisi</th>
                            <td>
                                @if ($inventaris->status_barang == 'Dalam Perbaikan')
                                <span class="badge bg-danger">Rusak</span>
                                @else
                                    @if ($inventaris->kondisi == 'Baik')
                                    <span class="badge bg-success">{{ $inventaris->kondisi }}</span>
                                    @elseif ($inventaris->kondisi == 'Rusak' || $inventaris->kondisi == 'Hilang')
                                    <span class="badge bg-danger">{{ $inventaris->kondisi }}</span>
                                    @else
                                    <span class="badge bg-warning">{{ $inventaris->kondisi }}</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Pembelian</th>
                            <td>{{ $inventaris->tgl_pembelian }}</td>
                        </tr>
                        <tr>
                            <th>Harga Barang</th>
                            <td>Rp. {{ number_format($inventaris->harga_barang, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                @foreach ($kategori as $item)
                                @if ($item->id_kategori == $inventaris->id_kategori)
                                {{ $item->nama_kategori }}
                                @endif
                                @endforeach
                            </td>
                        </tr>

                    </table>

                    <h3>QR Code</h3>
                    <div class="frame">
                        @if ($inventaris->qr_code)
                        <img src="{{ Storage::url('qrcodes/' . $inventaris->qr_code) }}" alt="QR Code" width="200px">
                        <br>
                        <a href="{{ Storage::url('qrcodes/' . $inventaris->qr_code) }}" download="{{ $inventaris->id_barang . ' | ' . $inventaris->nama_barang }}.png">
                            <button class="btn btn-info mt-2 text-white">
                                <i class="ri-download-2-fill"></i> Download QR Code
                            </button>
                        </a>
                        @else
                        <p>QR Code belum dibuat</p>
                        <a href="{{ route('inventaris.qr', $inventaris->id_barang) }}">
                            <button class="btn btn-primary mt-2 text-white">
                                <i class="ri-barcode-box-fill"></i> Generate QR Code
                            </button>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>