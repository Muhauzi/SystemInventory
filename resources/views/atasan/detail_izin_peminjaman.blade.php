<x-layout>
    <x-slot name="title">
        Data Peminjaman
    </x-slot>

    <x-pagetittle>
        Data Peminjaman
    </x-pagetittle>

    <x-alert></x-alert>

    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Menu Pimpinan</li>
                <li class="breadcrumb-item">Detail Izin Peminjaman</li>
                <li class="breadcrumb-item active">{{ $users->name }}</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('peminjaman.index') }}">
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
                                    @foreach ($detailPeminjaman as $brg)
                                    <li>
                                        <div class="row">
                                            <div class="col">
                                                {{ $brg->nama_barang }}
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>Total Nilai Barang Dipinjam</td>
                                <td>:</td>
                                <td>
                                    Rp. {{ number_format($nilai) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ $peminjaman->status }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <h4 class="text-center"
                                >Keputusan Izin</h4>
                            </div>
                            <div class="col-1">
                                <form action="{{ route('pimpinan.update_izin', $peminjaman->id_peminjaman) }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Disetujui">
                                    <button type="submit" class="btn btn-success btn-sm" title="Izinkan">
                                        <i class="ri-check-line"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-1">
                                <form action="{{ route('pimpinan.update_izin', $peminjaman->id_peminjaman) }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Tolak">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- sweetalert2 confirm tindakan persetujuan -->
     <script>
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault()
                var form = $(this)
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Anda akan melakukan tindakan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.unbind('submit').submit()
                    }
                })
            })
        })
     </script>
</x-layout>