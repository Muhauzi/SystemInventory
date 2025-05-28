<x-layout>
    <x-slot name="title">
        Form Pengembalian Barang
    </x-slot>

    <div class="pagetitle">
        <h1>Form Pengembalian Barang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Peminjaman</li>
                <li class="breadcrumb-item active">Form Pengembalian</li>
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
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header bg-primary text-light">
                        <b>Form Pengembalian</b>
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
                            <div class="mb-3">
                                <strong>Nama Peminjam:</strong> {{ $users->name }}<br>
                                <strong>Tanggal Pinjam:</strong> {{ $peminjaman->tgl_pinjam }}<br>
                                <strong>Tenggat Pengembalian:</strong> {{ $peminjaman->tgl_tenggat }}<br>
                                <strong>Tanggal Kembali:</strong> {{ $peminjaman->tgl_kembali ?? 'Belum Dikembalikan' }}
                            </div>

                            <hr>

                            <h5>Daftar Barang</h5>
                            @foreach ($barang as $index => $brg)
                            <div class="card mb-3 p-3 shadow-sm">
                                <div class="mb-2">
                                    <strong>ID Barang:</strong> {{ $brg->id_barang }}<br>
                                    <strong>Nama Barang:</strong> {{ $brg->nama_barang }}<br>
                                    <strong>Deskripsi dan Spesifikasi:</strong><br>{!! nl2br(e($brg->deskripsi_barang)) !!}
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input confirm-barang" type="checkbox" id="checkbox_{{ $brg->id_barang }}" data-id="{{ $brg->id_barang }}">
                                    <label class="form-check-label" for="checkbox_{{ $brg->id_barang }}">
                                        Barang sesuai deskripsi dan spesifikasi
                                    </label>
                                </div>
                                <div class="mb-2">
                                    <label for="kondisi_{{ $brg->id_barang }}"><strong>Kondisi Barang:</strong></label>
                                    <select class="form-select kondisi-select" name="kondisi[{{ $brg->id_barang }}]" id="kondisi_{{ $brg->id_barang }}">
                                        <option value="Baik">Baik</option>
                                        <option value="Rusak">Rusak</option>
                                        <option value="Hilang">Hilang</option>
                                    </select>
                                </div>
                            </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary" onclick="return confirmSave()">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Disable "Baik" if checkbox is not checked
        document.querySelectorAll('.confirm-barang').forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                const id = this.dataset.id;
                const select = document.getElementById('kondisi_' + id);
                const baikOption = select.querySelector('option[value="Baik"]');

                if (this.checked) {
                    baikOption.disabled = false;
                } else {
                    // Set value to Rusak or Hilang if "Baik" was selected
                    if (select.value === 'Baik') {
                        select.value = 'Rusak';
                    }
                    baikOption.disabled = true;
                }
            });

            // Inisialisasi saat halaman dimuat
            checkbox.dispatchEvent(new Event('change'));
        });

        function confirmSave() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data pengembalian akan disimpan.",
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
