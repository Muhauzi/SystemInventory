<x-layout>
    <x-slot name="title">
        Data Tagihan
    </x-slot>

    <div class="pagetitle">
        <h1>Detail Tagihan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Tagihan Denda</li>
                <li class="breadcrumb-item active">Detail Tagihan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <x-alert></x-alert>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-light">
                        <b>Data Tagihan Denda</b>
                    </div>
                    <div class="card-body mt-2 table-responsive">
                        <x-alert></x-alert>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="3">Data Pinjaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>ID Peminjaman</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $data['id_peminjaman'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Nama Peminjam</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $data['nama_peminjam'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Tanggal Pinjam</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $data['tgl_pinjam'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Tenggat Pengembalian</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $data['tgl_tenggat'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Tanggal Dikembalikan</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $data['tgl_kembali'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Barang Yang Dipinjam</b></td>
                                    <td><b>:</b></td>
                                    <td>
                                        <ul>
                                            @foreach ($data['barang'] as $item)
                                            <li>{{ $item['id_barang'] }} - {{ $item['nama_barang'] }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                            </tbody>
                        </table>
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th colspan="3">Data Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>ID Tagihan</b></td>
                                    <td><b>:</b></td>
                                    <td>{{ $data['id_tagihan'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Jumlah Denda</b></td>
                                    <td><b>:</b></td>
                                    <td>Rp. {{ number_format($data['jumlah_tagihan'], 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><b>Status Pembayaran</b></td>
                                    <td><b>:</b></td>
                                    <td>
                                        @if ($data['status_payment'] == 'capture' || $data['status_payment'] == 'settlement')
                                        <span class="badge bg-success">Lunas</span>
                                        @else
                                        <span class="badge bg-danger">Belum Dibayar</span>
                                        @endif
                                    </td>
                                </tr>
                                @if(auth()->user()->role == 'user' || auth()->user()->role == 'partnership')
                                <tr>
                                    <td><b>Bukti Pembayaran</b></td>
                                    <td><b>:</b></td>
                                    <td>
                                        @if ($data['bukti_pembayaran'] != null)
                                        <!-- Download file bukti pembayaran pdf  -->
                                        <a href="{{ asset('bukti_pembayaran/' . $data['bukti_pembayaran']) }}" target="_blank" download>
                                            <button type="button" class="btn btn-primary btn-sm">
                                                Download
                                            </button>
                                        </a>
                                        @else
                                        <x-form action="{{ route('user.tagihan.upload_bukti', $data['id_tagihan']) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="bukti_pembayaran" class="form-control" required>
                                            <x-slot name="customButton">
                                                <button type="submit" class="btn btn-primary my-2 btn-icon-text">
                                                    <i class="ri-upload-cloud-2-fill"></i> Upload Bukti Pembayaran
                                                </button>
                                            </x-slot>
                                        </x-form>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Link Pembayaran</b></td>
                                    <td><b>:</b></td>
                                    <td>
                                        @if ($data['status_payment'] == 'capture' || $data['status_payment'] == 'settlement')
                                        <button type="button" class="btn btn-success btn-sm" title="Tagihan Lunas">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Lunas
                                        </button>

                                        @elseif ($data['payment_url'] && $data['status_payment'] !='expire')
                                        <a href="{{ $data['payment_url'] }}" target="_blank" class="btn btn-success">Bayar Tagihan</a>
                                        @else
                                        <form action="{{ route('user.tagihan.bayar', $data['id']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-info">Buat Link Pembayaran</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>


                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ url()->previous() }}">
                                <button type="button" class="btn btn-primary my-2 btn-icon-text">
                                    <i class="ri-arrow-go-back-line"></i> Kembali
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>