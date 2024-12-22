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
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">

                <div class="d-flex justify-content-end mb-3">
                </div>
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('request_unduh_laporan_kerusakan') }}" class="btn btn-success"><i class="ri-download-2-line"></i>    Unduh Laporan</a>
                </div>
                <x-alert></x-alert>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tabel Laporan Kerusakan</h5>
                        <table class="table table-data table-hover">
                            <thead>
                                <tr>
                                    <th>Kode Laporan</th>
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
                                        <td>{{ $lk->nama_barang }}</td>
                                        <td>{{ $lk->nama_kategori }}</td>
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
                                                                        method="POST">
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
                                    @if ($lk->kondisi == 'Baik')
                                        <span class="badge bg-success">Telah Diperbaiki</span>
                                    @elseif ($lk->kondisi == 'Dalam Perbaikan')
                                        <span class="badge bg-warning">Dalam Perbaikan</span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ $lk->kondisi }}
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
                    </tbody>
                </table>
            </div>
        </div>
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
