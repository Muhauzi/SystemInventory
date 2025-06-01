<x-layout>

    <x-slot name="title">
        @if (Route::is('peminjaman'))
        {{ $title ?? 'Data Peminjam' }}
        @elseif(Route::is('peminjaman.pengembalian'))
        {{ $title ?? 'Data Pengembalian' }}
        @endif
    </x-slot>


    @if (Route::is('peminjaman.index'))
    <div class="pagetitle">
        <h1>List Peminjaman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kelola Transaksi</li>
                <li class="breadcrumb-item active">List Peminjaman</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    @elseif(Route::is('peminjaman.pengembalian'))
    <div class="pagetitle">
        <h1>List Pengembalian</h1>
       <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kelola Transaksi</li>
                <li class="breadcrumb-item active">List Pengembalian</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    @elseif (Route::is('peminjaman.laporan') || Route::is('pimpinan.laporan_transaksi'))
    <div class="pagetitle">
        <h1>List Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Form Unduh Laporan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    @endif




    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="d-flex justify-content-end mb-3">
                    <div class="my-2 flex sm:flex-row flex-col me-3">

                    </div>
                    <div class="block relative">
                        <!-- Trigger Modal -->
                        @if (Route::is('peminjaman.pengembalian'))
                        <button type="button" class="btn btn-primary my-2 btn-icon-text" data-bs-toggle="modal" data-bs-target="#returnModal">
                            <i class="ri-add-fill"></i> Tambah Pengembalian
                        </button>
                        @endif


                        @if (Route::is('peminjaman.index'))
                        <a href="{{ route('peminjaman.add') }}">
                            <button type="button" class="btn btn-primary my-2 btn-icon-text">
                                <i class="ri-add-fill"></i> Tambah
                            </button>
                        </a>
                        @endif

                    </div>
                    <div class="block">
                        <!-- Button trigger modal -->
                        @if (Route::is('peminjaman.index'))
                        <button type="button" class="btn btn-secondary my-2 btn-icon-text m-2"
                            data-bs-toggle="modal" data-bs-target="#scanModal">
                            <i class="ri-scan-2-line"></i> Scan Peminjaman
                        </button>
                        @elseif (Route::is('peminjaman.laporan') || Route::is('pimpinan.laporan_transaksi'))
                        <a href="{{ route('request_unduh_laporan_transaksi') }}"
                            class="btn btn-success ms-2 my-2 btn-icon-text"><i class="ri-download-2-line"></i> Unduh
                            Laporan</a>
                        @endif


                    </div>
                </div>

                <x-alert></x-alert>

                <div class="card">
                    <div class="card-body">
                        @if (Route::is('peminjaman.index'))
                        <h5 class="card-title">Tabel Peminjaman</h5>
                        @elseif(Route::is('peminjaman.pengembalian'))
                        <h5 class="card-title">Tabel Pengembalian</h5>
                        @elseif (Route::is('peminjaman.laporan') || Route::is('pimpinan.laporan_transaksi'))
                        <h5 class="card-title">Tabel Transaksi</h5>
                        @endif
                        <!-- Small tables -->
                        <div class="table-responsive">
                            <table class="table table-borderless datatable table-hover">
                                <thead style="background-color: rgba(233, 239, 248, 0.5);">
                                    <tr>
                                        <th scope="col">Kode Peminjaman</th>
                                        <th scope="col">Nama Peminjam</th>
                                        <th scope="col">Role Peminjam</th>
                                        <th scope="col">Tanggal Pinjam</th>
                                        <th scope="col">Tenggat Pengembalian</th>
                                        @if(Route::is('peminjaman.pengembalian'))
                                        <th scope="col">Tanggal Kembali</th>
                                        @endif
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($peminjaman as $item)
                                    <tr>
                                        <th scope="row">{{ $item->id_peminjaman }}</th>
                                        <td>
                                            @foreach ($users as $user)
                                            @if ($user->id == $item->id_user)
                                            {{ $user->name }}
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($users as $user)
                                            @if ($user->id == $item->id_user)
                                            @if ($user->role == 'user')
                                            Karyawan
                                            @elseif ($user->role == 'partnership')
                                            Partnership
                                            @endif
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $item->tgl_pinjam }}</td>
                                        <td>{{ $item->tgl_tenggat }}</td>
                                        @if(Route::is('peminjaman.pengembalian'))
                                        <td>{{ $item->tgl_kembali ?? 'Belum Dikembalikan' }}</td>
                                        @endif
                                        <td>
                                            @if ($item->status == 'Dipinjam')
                                            <span class="badge bg-warning">Dipinjam</span>
                                            @elseif ($item->status == 'Dikembalikan')
                                            @if($item->tgl_kembali > $item->tgl_tenggat)
                                            <span class="badge bg-danger">Dikembalikan - Terlambat</span>
                                            @else
                                            <span class="badge bg-success">Dikembalikan</span>
                                            @endif
                                            @else
                                            <span class="badge bg-info">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('peminjaman.show', $item->id_peminjaman) }}">
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i class="ri-eye-fill"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                        </div>
                        </table>
                        <!-- End small tables -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Modal Gabungan: Input Manual & Scan -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Header Modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Pengembalian Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Tab Navigation -->
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="returnTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">Input Manual</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="scan-tab" data-bs-toggle="tab" data-bs-target="#scan" type="button" role="tab">Scan QR</button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="returnTabContent">

                        <!-- Tab: Input Manual -->
                        <div class="tab-pane fade show active" id="manual" role="tabpanel">
                            <form action="{{ route('peminjaman.manualReturn') }}" method="post">
                                @csrf
                                @method('POST')
                                <div class="mb-3">
                                    <label for="id_peminjaman" class="form-label">ID Peminjaman</label>
                                    <input type="text" class="form-control" id="id_peminjaman" name="id_peminjaman" placeholder="Masukkan ID Peminjaman">
                                </div>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </form>
                        </div>

                        <!-- Tab: Scan QR -->
                        <div class="tab-pane fade" id="scan" role="tabpanel">
                            <div class="reader" id="scannerBarang"></div>
                            <form action="{{ route('peminjaman.scanReturn') }}" id="formScanFind" method="post">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="id_peminjaman" id="id_barang">
                                @if (session('id_barang'))
                                <div class="alert alert-success mt-3">
                                    Peminjaman ditemukan
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
        <!-- Modal Scan Peminjaman -->
    <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scanModalLabel">Scan QR Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="reader" id="scannerPeminjaman"></div>
                    <form action="{{ route('peminjaman.scanQR') }}" id="formScanFind2" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id_peminjaman" id="id_peminjaman2">
                        <div class="alert alert-info mt-3">
                            Silakan scan QR Code untuk menemukan peminjaman.
                        </div>
                        <div id="scan-debug" class="mt-3"></div>
                        @if (session('id_peminjaman'))
                        <div class="alert alert-success mt-3">
                            Peminjaman ditemukan
                        </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>



    <script src="{{ asset('html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script>
        // Initialize scanner for Pengembalian (scannerBarang)
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