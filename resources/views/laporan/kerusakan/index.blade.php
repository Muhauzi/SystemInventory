<x-layout>

    <x-slot name="title">
        Laporan Kerusakan
    </x-slot>

    <div class="pagetitle">
        <h1>Laporan Kerusakan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Laporan Kerusakan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section>
        <div class="row justify-content-center">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('request_unduh_laporan_kerusakan') }}" class="btn btn-success"><i class="ri-download-2-line"></i> Form Unduh Laporan</a>
            </div>
            <x-alert></x-alert>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tabel Laporan Kerusakan</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless datatable table-hover">
                            <thead style="background-color: rgba(233, 239, 248, 0.5);">
                                <tr>
                                    <th>Kode Laporan</th>
                                    <th>Peminjam</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Biaya Perbaikaan</th>
                                    <th>Status Pembayaran</th>
                                    <th>Status Barang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan_kerusakan as $lk)
                                <tr>
                                    <td>{{ $lk->id }}</td>
                                    <td>{{ $lk->detailPeminjaman->peminjaman->user->name }}</td>
                                    <td>{{ $lk->detailPeminjaman->barang->nama_barang }}</td>
                                    <td>{{ $lk->detailPeminjaman->barang->kategoriBarang->nama_kategori }}</td>
                                    @if ($lk->detailPeminjaman->peminjaman->user->role == 'partnership')
                                    <td>
                                        @php
                                        $tagihanFound = false;
                                        @endphp
                                        @foreach ($tagihan as $t)
                                        @if ($t->id_laporan_kerusakan == $lk->id)
                                        Rp{{ number_format($t->total_tagihan, 0, ',', '.') }}
                                        @php
                                        $tagihanFound = true;
                                        @endphp
                                        @endif
                                        @endforeach
                                        @if (!$tagihanFound)
                                        @if (Auth::user()->role == 'pimpinan')
                                        Belum ada tagihan
                                        @else
                                        <div class="makeTagihan">
                                            <button type="button" class="btn btn-primary"
                                                data-bs-toggle="modal" data-bs-target="#modalTagihan-{{ $lk->id }}">
                                                Buat Tagihan
                                            </button>
                                            <!-- modal tagihan -->
                                            <div class="modal fade" id="modalTagihan-{{ $lk->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                                Buat Tagihan</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('laporan_kerusakan.storeTagihan') }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <input type="hidden" name="id_lk"
                                                                        value="{{ $lk->id }}">
                                                                    <label for="biaya_perbaikan"
                                                                        class="form-label">Biaya
                                                                        Perbaikan</label>
                                                                    <input type="number"
                                                                        class="form-control"
                                                                        id="biaya_perbaikan"
                                                                        name="biaya_perbaikan" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="nota_perbaikan"
                                                                        class="form-label">Nota
                                                                        Perbaikan</label>
                                                                    <input type="file"
                                                                        class="form-control"
                                                                        id="nota_perbaikan"
                                                                        name="nota_perbaikan" accept="image/*"
                                                                        required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endif
                                    </td>
                                    @elseif ($lk->detailPeminjaman->peminjaman->user->role == 'user')
                                    <td>
                                        Karyawan
                                    </td>
                                    @endif
                                    <td>
                                        @php
                                        $tagihanFound = false;
                                        @endphp
                                        @foreach ($tagihan as $t)
                                        @if ($t->id_laporan_kerusakan == $lk->id)
                                        @if ($t->status == 'capture' || $t->status == 'settlement')
                                        <span class="badge bg-success">Lunas</span>
                                        @elseif ($t->status == 'pending')
                                        <span class="badge bg-warning">Menunggu Pembayaran</span>
                                        @else
                                        <span class="badge bg-danger">{{ $t->status }}</span>
                                        @endif
                                        @php
                                        $tagihanFound = true;
                                        @endphp
                                        @break
                                        @endif
                                        @endforeach

                                        @if (!$tagihanFound && Auth::user()->role == 'pimpinan')
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($lk->detailPeminjaman->barang->kondisi == 'Baik')
                                        <span class="badge bg-success">Telah Diperbaiki</span>
                                        @elseif ($lk->detailPeminjaman->barang->kondisi == 'Dalam Perbaikan')
                                        <span class="badge bg-warning">Dalam Perbaikan</span>
                                        @else
                                        <span class="badge bg-danger">
                                            {{ $lk->detailPeminjaman->barang->kondisi }}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('laporan_kerusakan.show', $lk->id) }}">
                                            <button type="button" class="btn btn-info btn-sm" title="Detail">
                                                <i class="ri-eye-fill"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                        </table>
                    </div>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script>
        // initialize html5QRCodeScanner
        let html5QRCodeScanner = new Html5QrcodeScanner(
            "scannerBarang", {
                fps: 10,
                qrbox: {
                    width: 500,
                    height: 500,
                },
            }
        );

        function onScanSuccess(decodedText, decodedResult) {
            // set the value of the hidden input field with the scanned text
            document.getElementById('id_barang').value = decodedText;

            // submit the form after setting the value
            document.getElementById('formScanFind').submit();

            // clear the scan area after performing the action above
            html5QRCodeScanner.clear();
        }
        html5QRCodeScanner.render(onScanSuccess);
    </script>
</x-layout>