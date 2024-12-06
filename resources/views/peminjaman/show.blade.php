<x-layout>
    <x-slot name="title">
        Data Peminjaman
    </x-slot>

    <x-pagetittle>
        Data Peminjaman
    </x-pagetittle>

    <x-alert></x-alert>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ url()->previous() }}">
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
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if ($peminjaman->status == 'Pending')
                                    <span class="badge bg-warning">Menunggu Persetujuan Atasan</span>
                                    @elseif ($peminjaman->status == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                    @elseif ($peminjaman->status == 'Ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @else
                                    <span class="badge bg-info">
                                        {{$peminjaman->status}}
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @if ($peminjaman->status == 'Dipinjam')
                            <tr>
                                <td>Tanggal Kembali</td>
                                <td>:</td>
                                <td>
                                    {{ $peminjaman->tgl_kembali ?? 'Belum Dikembalikan' }}
                                </td>
                            </tr>
                            @endif
                            @if ($peminjaman->status == 'Dikembalikan')
                            <tr>
                                <td>Surat Bukti Pengembalian</td>
                                <td>:</td>
                                <td>
                                    <a href="{{route('peminjaman.buktiPinjam', $peminjaman->id_peminjaman)}}" target="_blank">
                                        <button class="btn btn-sm btn-info text-light">
                                            <i class="ri-file-download-line"></i> Download Bukti Pengembalian
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @elseif ($peminjaman->status == 'Dipinjam')
                            <tr>
                                <td>Surat Bukti Peminjaman</td>
                                <td>:</td>
                                <td>
                                    <a href="{{route('peminjaman.buktiPinjam', $peminjaman->id_peminjaman)}}" target="_blank">
                                        <button class="btn btn-sm btn-info text-light">
                                            <i class="ri-file-download-line"></i> Download Bukti Peminjaman
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Barang</td>
                                <td>:</td>
                                <td>
                                    <ul>
                                        @foreach ($detailPeminjaman as $brg)
                                        <li>
                                            <div class="row">
                                                <div class="col">
                                                    {{ $brg->nama_barang }}
                                                </div>
                                                @if ($brg->kondisi == 'Rusak')
                                                <div class="col">
                                                    <a href="{{ route('laporan_kerusakan.add', $brg->id) }}">
                                                        <button class="btn btn-sm btn-danger">
                                                            <i class="ri-error-warning-line"></i> Laporkan Kerusakan
                                                        </button>
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        </table>
                        @if ($peminjaman->status == 'Dipinjam')
                        <div class="d-flex justify-content-end ">
                            <a href="{{ route('peminjaman.edit', $peminjaman->id_peminjaman) }}">
                                <button class="btn btn-warning">
                                    <i class="ri-pencil-line"></i> Edit
                                </button>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>