<x-layout>

    <x-slot name="title">
        Laporan Kerusakan
    </x-slot>

    <x-pagetittle>
        Laporan Kerusakan
    </x-pagetittle>

    <section>
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">

                <div class="d-flex justify-content-end mb-3">
                </div>
                <x-alert></x-alert>
                <div class="my-2 flex sm:flex-row flex-col">
                    <div class="block relative">
                        <div class="table-responsive">
                            <table class="table table-data text-center">
                                <thead>
                                    <tr>
                                        <th>Kode Laporan</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Biaya Perbaikaan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporan_kerusakan as $lk)
                                    <tr>
                                        <td>{{ $lk->id }}</td>
                                        <td>{{ $lk->nama_barang }}</td>
                                        <td>{{ $lk->nama_kategori }}</td>
                                        <td>-</td>
                                        <td>
                                            @if ($lk->kondisi == 'Diperbaiki')
                                            <span class="badge bg-success">Diperbaiki</span>
                                            @elseif ($lk->kondisi == 'Sedang Diperbaiki')
                                            <span class="badge bg-warning">Sedang Diperbaiki</span>
                                            @else
                                            <span class="badge bg-danger">Rusak</span>
                                            @endif
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