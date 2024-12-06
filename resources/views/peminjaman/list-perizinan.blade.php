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