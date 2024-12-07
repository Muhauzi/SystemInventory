<x-layout>
    <x-slot name="title">
        Edit Peminjaman
    </x-slot>

    <div class="pagetitle">
        <h1>Form Edit Peminjaman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Peminjaman</li>
                <li class="breadcrumb-item active">Form Edit Peminjaman</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <x-alert></x-alert>

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
                        <b>Edit Peminjaman</b>
                    </div>
                    <div class="card-body mt-2">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form method="POST" id="formEdit" action="{{ route('peminjaman.update', $peminjaman->id_peminjaman) }}">
                            @csrf
                            @method('POST')
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
                                    <td>Tanggal Kembali</td>
                                    <td>:</td>
                                    <td>
                                        {{ $peminjaman->tgl_kembali ?? 'Belum Dikembalikan' }}
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
                                        @if ($peminjaman->status == 'Dikembalikan')
                                        <span class="badge bg-success">
                                            {{ $peminjaman->status }}
                                        </span>
                                        @else
                                        <select class="form-select" name="status">
                                            @if ($peminjaman->status == 'Disetujui')
                                            <option value="Dipinjam" {{ $peminjaman->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                            @elseif ($peminjaman->status == 'Dipinjam') 
                                            <option value="Dikembalikan" {{ $peminjaman->status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                            @endif
                                        </select>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kondisi Barang</td>
                                    <td>:</td>
                                    <td>
                                        @foreach ($barang as $brg)
                                        <div class="mb-2">
                                            <label>{{ $brg->nama_barang }}</label>
                                            <select class="form-select" name="kondisi[{{ $brg->id_barang }}]">
                                                <option value="Baik" {{ $brg->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="Rusak" {{ $brg->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                                <option value="Hilang" {{ $brg->kondisi == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                            </select>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                </tr>
                            </table>
                            <button type="submit" class="btn btn-primary" onclick="return confirmSave()">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmSave() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('#formEdit').submit();
                }
            });
            return false;
        }
    </script>
</x-layout>