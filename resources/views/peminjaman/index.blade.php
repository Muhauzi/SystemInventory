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
                    <li class="breadcrumb-item active">Peminjaman</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
    @elseif(Route::is('peminjaman.pengembalian'))
        <div class="pagetitle">
            <h1>List Pengembalian</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Pengembalian</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        @elseif (Route::is('peminjaman.laporan') || Route::is('pimpinan.laporan_transaksi'))
        <div class="pagetitle">
            <h1>List Transaksi</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Laporan Transaksi</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
    @endif




    <section>
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">

                <div class="d-flex justify-content-end mb-3">
                    <div class="my-2 flex sm:flex-row flex-col me-3">

                    </div>
                    <div class="block relative">
                        @if (Route::is('peminjaman.index') || Route::is('peminjaman.pengembalian'))
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
                        @elseif(Route::is('peminjaman.pengembalian'))
                            <button type="button" class="btn btn-secondary my-2 btn-icon-text m-2"
                                data-bs-toggle="modal" data-bs-target="#scanModal">
                                <i class="ri-scan-2-line"></i> Scan Pengembalian
                            </button>
                        @elseif (Route::is('peminjaman.laporan') || Route::is('pimpinan.laporan_transaksi'))
                            <a href="{{ route('request_unduh_laporan_transaksi') }}"
                                class="btn btn-success ms-2 my-2 btn-icon-text"><i class="ri-download-2-line"></i> Unduh
                                Laporan</a>
                        @endif

                        <!-- Modal -->
                        <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="scanModalLabel">Scan Pengembalian</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="reader" id="scannerBarang"></div>
                                        <form action="{{ route('peminjaman.scanReturn') }}" id="formScanFind"
                                            method="post">
                                            @csrf
                                            <input type="hidden" name="id_barang" id="id_barang">
                                            @if (session('id_barang'))
                                                <div class="alert alert-success mt-3">
                                                    Peminjaman ditemukan
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <table class="table table-data table-sm table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Kode Peminjaman</th>
                                    <th scope="col">Nama Peminjam</th>
                                    <th scope="col">Tanggal Pinjam</th>
                                    <th scope="col">Tenggat Pengembalian</th>
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
                                        <td>{{ $item->tgl_pinjam }}</td>
                                        <td>{{ $item->tgl_kembali ?? 'Belum Dikembalikan' }}</td>
                                        <td>
                                            @if ($item->status == 'Dipinjam')
                                                <span class="badge bg-warning">Dipinjam</span>
                                            @elseif ($item->status == 'Dikembalikan')
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @else
                                                <span class="badge bg-info">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('peminjaman.show', $item->id_peminjaman) }}">
                                                <button type="button" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="ri-eye-fill"></i>
                                                </button>
                                            </a>
                                            @if ($item->status == 'Dipinjam')
                                                <a href="{{ route('peminjaman.edit', $item->id_peminjaman) }}">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        title="Edit">
                                                        <i class="ri-pencil-line"></i>
                                                    </button>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End small tables -->

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
