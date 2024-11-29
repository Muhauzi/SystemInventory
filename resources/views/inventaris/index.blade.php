<x-layout>
    <x-slot name="title">
        Barang Inventaris
    </x-slot>


    <x-pagetittle>
        Barang Inventaris
    </x-pagetittle>

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-secondary my-2 btn-icon-text m-2" data-bs-toggle="modal" data-bs-target="#scanModal">
            <i class="ri-scan-2-line"></i> Scan Qr Barang
        </button>
        <!-- Modal -->
        <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scanModalLabel">Scan Qr</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="reader" id="scannerBarang"></div>
                        <form action="{{ route('inventaris.scanQR') }}" id="formScanFind" method="post">
                            @csrf
                            <input type="hidden" name="id_barang" id="id_barang">
                            @if (session('id_barang'))
                            <div class="alert alert-success mt-3">
                                Barang ditemukan
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-alert></x-alert>
                        <div class="row p-4">
                            <h3 class="row justify-content-center p-2">Kategori Barang</h3>
                            @foreach ($kategori as $list)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $list->nama_kategori }}</h5>
                                        <p class="card-text">Jumlah Barang :
                                            @php
                                            $idKategori = $list->id_kategori;
                                            $jumlahBarang = \App\Models\Inventaris::where('id_kategori', $idKategori)->count();
                                            echo $jumlahBarang;
                                            @endphp
                                        </p>
                                        <a href="{{ route('inventaris.list', $list->id_kategori) }}" class="btn btn-primary">Lihat Barang</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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