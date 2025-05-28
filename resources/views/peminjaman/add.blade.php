<x-layout>
    <x-slot name="title">
        Tambah Peminjaman
    </x-slot>

    <div class="pagetitle">
        <h1>Form Tambah Peminjaman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Peminjaman</li>
                <li class="breadcrumb-item active">Form Tambah Peminjaman</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->



    <x-section>
        <div class="m-4">
            <x-alert></x-alert>
        </div>
        <div class="card">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="card-body">

                <h5 class="card-title">Tambah Peminjaman</h5>
                <x-alert></x-alert>
                <form method="post" action="{{ route('peminjaman.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Peminjam</label>
                        <input type="hidden" name="id_user" id="selected-user-id">
                        <input type="text" id="search-user-input" class="form-control mb-2" placeholder="Cari Peminjam...">
                        <ul id="user-suggestions" class="list-group"></ul>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control">
                        </div>
                        <div class="col">
                            <label for="tgl_kembali" class="form-label">Tenggat Pengembalian</label>
                            <input type="date" name="tgl_tenggat" id="tgl_kembali" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                    </div>
                    <div class="row" id="barang-container">
                        <h4>List Barang Yang Akan Dipinjam</h4>
                        <!-- Barang items will be added here dynamically -->
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="scanModalLabel">Scan Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="scannerBarang"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="scan-barang" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#scanModal">Scan Barang</button>
                    <br>
                    <button type="submit"
                        class="btn btn-primary {{ $inventaris->count() == 0 ? 'disabled' : '' }}">Tambah</button>
                    <a href="{{ route('peminjaman.index') }}">
                        <button type="button" class="btn btn-secondary my-2 btn-icon-text">Kembali
                        </button>
                    </a>
                </form>
            </div>
        </div>
    </x-section>
    <script src="{{ asset('html5-qrcode/html5-qrcode.min.js') }}"></script>
    <div id="data-inventaris" data-inventaris="{{ json_encode($inventaris) }}"></div>
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
        const dataContainer = document.getElementById('data-inventaris');
        const barangList = JSON.parse(dataContainer.dataset.inventaris);


        function getBarangName(id) {
            let barang = barangList.find(item => item.id_barang == id);
            return barang ? barang.nama_barang : "Barang tidak ditemukan/Tidak Tersedia";
        }


        function onScanSuccess(decodedText, decodedResult) {
            if (barang = barangList.find(item => item.id_barang == decodedText)) {
                let barangContainer = document.getElementById('barang-container');
                let barangItem = document.createElement('div');
                barangItem.classList.add('barang-item', 'mb-2');
                barangItem.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <span>${getBarangName(decodedText)}</span>
                        <button type="button" class="btn btn-danger remove-barang">Hapus</button>
                        <input type="hidden" name="id_barang[]" value="${decodedText}">
                    </div>
                `;
                barangContainer.appendChild(barangItem);
                // close the modal
                let modal = bootstrap.Modal.getInstance(document.getElementById('scanModal'));
                modal.hide();
            } else {
                alert('Barang tidak ditemukan/Tidak Tersedia');
                html5QRCodeScanner.clear();
                html5QRCodeScanner.render(onScanSuccess);

            }
        }
        html5QRCodeScanner.render(onScanSuccess);

        document.getElementById('barang-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-barang')) {
                e.target.closest('.barang-item').remove();
            }
        });

        document.getElementById('scan-barang').addEventListener('click', function() {
            html5QRCodeScanner.clear();
            html5QRCodeScanner.render(onScanSuccess);
        });

        // Select Js

        const users = @json($users);
        const input = document.getElementById('search-user-input');
        const hiddenInput = document.getElementById('selected-user-id');
        const suggestionBox = document.getElementById('user-suggestions');

        input.addEventListener('input', function() {
            const search = input.value.toLowerCase();
            suggestionBox.innerHTML = '';

            if (search === '') return;

            const filteredUsers = users.filter(user =>
                user.name.toLowerCase().includes(search)
            );

            filteredUsers.forEach(user => {
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'list-group-item-action');
                li.textContent = user.name;
                li.dataset.userId = user.id;

                li.addEventListener('click', function() {
                    input.value = user.name;
                    hiddenInput.value = user.id;
                    suggestionBox.innerHTML = '';
                });

                suggestionBox.appendChild(li);
            });
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestionBox.contains(e.target)) {
                suggestionBox.innerHTML = '';
            }
        });
    </script>

    <style>
        #user-suggestions {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>

</x-layout>