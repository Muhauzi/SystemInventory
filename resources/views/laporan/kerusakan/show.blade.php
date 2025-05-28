<x-layout>
    <x-slot name="title">
        Detail Kerusakan Barang
    </x-slot>

    <div class="pagetitle">
        <h1>Detail Kerusakan Barang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Laporan Kerusakan</li>
                <li class="breadcrumb-item active">Detail Kerusakan Barang</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <x-alert></x-alert>

    @if (auth()->user()->role == 'admin')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('laporan_kerusakan.index') }}">
            <button type="button" class="btn btn-primary my-2 btn-icon-text">
                <i class="ri-arrow-go-back-line"></i> Kembali
            </button>
        </a>
    </div>
    @else
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('user.TagihanKerusakan') }}">
            <button type="button" class="btn btn-primary my-2 btn-icon-text">
                <i class="ri-arrow-go-back-line"></i> Kembali
            </button>
        </a>
    </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="row">
                    @foreach ($bukti_kerusakan as $foto)
                    <div class="col-4">
                        <button type="button" class="btn btn-link" data-bs-toggle="modal"
                            data-bs-target="#modal{{ $foto['id'] }}">
                            <img src="{{ asset('storage/buktiKerusakan/' . $foto['foto']) }}" class="img-thumbnail" alt="...">
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary text-light">
                        <b>
                            Detail Kerusakan Barang
                        </b>
                    </div>
                    <div class="card-body mt-2">
                        <x-alert></x-alert>
                        <table class="table table-borderless">
                            <tr>
                                <td>Nama Barang</td>
                                <td>:</td>
                                <td>
                                    {{ $laporan_kerusakan->nama_barang }}
                                </td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>:</td>
                                <td>
                                    {{ $laporan_kerusakan->nama_kategori }}
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            @if ($laporan_kerusakan->kondisi == 'Baik')
                                            <span class="badge bg-success">Telah Diperbaiki</span>
                                            @elseif ($laporan_kerusakan->kondisi == 'Dalam Perbaikan')
                                            <span class="badge bg-warning">Dalam Perbaikan</span>
                                            @else
                                            <span class="badge bg-danger">Rusak</span>
                                            @endif
                                        </div>
                                        <div class="col">

                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td>
                                    {{ $laporan_kerusakan->deskripsi_kerusakan }}
                                </td>
                            </tr>
                            <tr>
                                <td>Biaya Perbaikan</td>
                                <td>:</td>
                                <td>
                                    @if ($tagihan && $tagihan->total_tagihan != null)
                                    Rp. {{ number_format($tagihan->total_tagihan) }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Status Tagihan</td>
                                <td>:</td>
                                <td>
                                    @if ($tagihan && $tagihan->status == null)
                                    <span class="badge bg-warning">Belum Dibuat</span>
                                    @elseif ($tagihan && $tagihan->status == 'Belum Lunas')
                                    <span class="badge bg-warning">Belum Lunas</span>
                                    @elseif ($tagihan && ($tagihan->status == 'capture' || $tagihan->status == 'settlement'))
                                    <span class="badge bg-success">Lunas</span>
                                    @else
                                    <span class="badge bg-danger">{{ $tagihan->status ?? 'Tidak Diketahui' }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($bukti_kerusakan as $foto)
    <div class="modal fade" id="modal{{ $foto['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Foto Kerusakan Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('storage/bukti_kerusakan/' . $foto['foto']) }}" class="img-thumbnail" alt="...">
                </div>
            </div>
        </div>
    </div>
    @endforeach
</x-layout>