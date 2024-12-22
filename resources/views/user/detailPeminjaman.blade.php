<x-layout>
    <x-slot name="title">
        Data Peminjaman
    </x-slot>

    <div class="pagetitle">
        <h1>Detail Peminjaman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="route('dashboard')">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Detail Peminjamaan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <x-alert></x-alert>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('user.riwayat_peminjaman') }}">
            <button type="button" class="btn btn-primary my-2 btn-icon-text">
                <i class="ri-arrow-go-back-line"></i> Kembali
            </button>
        </a>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-light">
                        <b>Data Peminjaman</b>
                    </div>
                    <div class="card-body mt-2">
                        <x-alert></x-alert>
                        <table class="table table-borderless">
                            <tr>
                                <td>Nama Peminjam</td>
                                <td>:</td>
                                <td>
                                    {{ $users->name }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Pinjam</td>
                                <td>:</td>
                                <td>
                                    {{ $peminjaman->tgl_pinjam }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tenggat Pengembalian</td>
                                <td>:</td>
                                <td>
                                    {{ $peminjaman->tgl_tenggat }}
                                </td>
                            </tr>
                            <tr>
                                <td>Barang</td>
                                <td>:</td>
                                <td>
                                    @foreach ($barang as $brg)
                                        <li>{{ $brg->nama_barang }}</li>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if ($peminjaman->status == 'Dipinjam')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @elseif ($peminjaman->status == 'Dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Kembali</td>
                                <td>:</td>
                                <td>
                                    {{ $peminjaman->tgl_kembali ?? 'Belum Dikembalikan' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Surat Bukti Peminjaman</td>
                                <td>:</td>
                                <td>
                                    <a href="{{ route('user.buktiPinjam', $peminjaman->id_peminjaman) }}"
                                        target="_blank">
                                        <button class="btn btn-sm btn-info text-light">
                                            <i class="ri-file-download-line"></i> Download Invoice Pengembalian
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
