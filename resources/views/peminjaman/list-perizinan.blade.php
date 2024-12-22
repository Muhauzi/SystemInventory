<x-layout>

    <x-slot name="title">
        {{
            $title ?? 'Data Peminjam'
        }}
    </x-slot>

    <div class="pagetitle">
        <h1>List Izin Peminjaman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Peminjaman</li>
                <li class="breadcrumb-item active">List Izin Peminjaman</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section>
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">
                <x-alert></x-alert>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tabel Izin Peminjaman</h5>
                        <div class="mb-4">
                            <h6>Batas Nominal : Rp.{{ number_format($batasPeminjaman->batas_nominal, 0, ',', '.') }}</h6>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateBatasNominalModal">
                                Update Batas Nominal
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="updateBatasNominalModal" tabindex="-1" aria-labelledby="updateBatasNominalModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('peminjaman.updateBatasPeminjaman', $batasPeminjaman->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateBatasNominalModalLabel">Update Batas Nominal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="batas_nominal" class="form-label">Batas Nominal</label>
                                                    <input type="number" class="form-control" id="batas_nominal" name="batas_nominal" value="{{ $batasPeminjaman->batas_nominal }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-data table-hover">
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
                                        <td>{{ $item->tgl_kembali ?? '-' }}</td>
                                        <td>
                                            @if ($item->status == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif ($item->status == 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                            @elseif ($item->status == 'Ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('peminjaman.show', $item->id_peminjaman) }}">
                                                <button type="button" class="btn btn-info btn-sm" title="Detail">
                                                    <i class="ri-eye-fill"></i>
                                                </button>
                                            </a>
                                            @if ($item->status == 'Disetujui')
                                            <a href="{{ route('peminjaman.edit', $item->id_peminjaman) }}">
                                                <button type="button" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                            </a>
                                            @endif
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