<x-layout>

    <x-slot name="title">
        {{
            $title ?? 'Data Peminjam'
        }}
    </x-slot>

    <x-pagetittle>
        {{
            $title ?? 'Data Peminjam'
        }}
    </x-pagetittle>

    <section>
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">

                <div class="d-flex justify-content-end mb-3">
                    <div class="my-2 flex sm:flex-row flex-col me-3">
                        <div class="block relative">
                            <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                                <i class="bi bi-search"></i>
                            </span>
                            <input placeholder="Search" class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
                        </div>
                    </div>
                    <div class="block relative">
                        <a href="{{ route('peminjaman.add') }}">
                            <button type="button" class="btn btn-primary my-2 btn-icon-text">
                                <i class="ri-add-fill"></i> Tambah
                            </button>
                        </a>
                    </div>
                    <div class="block">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-secondary my-2 btn-icon-text m-2" data-bs-toggle="modal" data-bs-target="#scanModal">
                            <i class="ri-scan-2-line"></i> Scan Pengembalian
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="scanModalLabel">Scan Pengembalian</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="reader" id="scannerBarang"></div>
                                        <form action="{{ route('peminjaman.scanReturn') }}" id="formScanFind" method="post">
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

                <div class="my-2 flex sm:flex-row flex-col">
                    <div class="block relative">
                        <div class="table-responsive">
                            <table class="table table-data text-center">
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