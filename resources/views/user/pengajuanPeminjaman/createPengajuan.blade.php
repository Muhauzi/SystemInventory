<x-layout>
    <style>
        .dropdown-list {
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
            z-index: 10;
        }

        .selected-item {
            display: inline-block;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 5px 10px;
            margin-right: 5px;
            margin-top: 5px;
        }
    </style>

    <x-slot name="title">
        Tambah Pengajuan Peminjaman Barang
    </x-slot>

    <div class="pagetitle">
        <h1>Form Pengajuan Peminjaman Barang</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Peminjaman</li>
                <li class="breadcrumb-item active">Form Pengajuan Peminjaman Barang</li>
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

                <h5 class="card-title">Ajukan Peminjaman</h5>
                <x-alert></x-alert>
                <form method="post" action="{{ route('user.savePengajuan') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Peminjam</label>
                        <input type="text" class="form-control" id="peminjam_name" disabled value="{{ auth()->user()->name }}"
                            readonly>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="tgl_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="tgl_kembali" class="form-label">Rencana Pengembalian</label>
                            <input type="date" name="tgl_tenggat" id="tgl_kembali" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control"  required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="surat_pengantar" class="form-label">Surat Pengantar</label>
                        <span class="text-muted">*Format PDF</span>
                        <input type="file" class="form-control" id="surat_pengantar" name="surat_pengantar" accept=".pdf" required>
                    </div>
                    <div class="row" id="barang-container">
                        <h4>List Barang Yang Akan Dipinjam</h4>
                        <!-- Barang items will be added here dynamically -->
                    </div>
                    <div class="mb-3">
                        <label for="searchInput" class="form-label">Pilih Barang</label>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari barang...">
                        <div id="dropdownList" class="dropdown-list"></div>
                    </div>
                    <div class="mb-3">
                        <label for="searchInput" class="form-label">Barang yang dipilih</label>
                        <div id="selectedItems" class="selected-items">
                        <!-- Selected items will be displayed here -->
                    </div>
                    </div>
                    
                    <input type="hidden" name="barang" id="barang" required>

                    <br>
                    <button type="submit" id="submitButton" class="btn btn-primary" disabled>Tambah</button>

                    <a href="{{ route('peminjaman.index') }}">
                        <button type="button" class="btn btn-secondary my-2 btn-icon-text">Kembali
                        </button>
                    </a>
                </form>
            </div>
        </div>
    </x-section>
    <script src="{{ asset('html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script>
        const items = @json($listBarang);
        const searchInput = document.getElementById('searchInput');
        const dropdownList = document.getElementById('dropdownList');
        const selectedItems = document.getElementById('selectedItems');
        const barangInput = document.getElementById('barang');

        let selected = [];

        searchInput.addEventListener('input', () => {
            const keyword = searchInput.value.toLowerCase();
            dropdownList.innerHTML = '';

            if (keyword.trim() === '') {
                dropdownList.style.display = 'none';
                return;
            }

            const filtered = items.filter(item =>
                item.nama_barang.toLowerCase().includes(keyword) &&
                !selected.some(sel => sel.id_barang === item.id_barang)
            );

            if (filtered.length === 0) {
                dropdownList.style.display = 'none';
                return;
            }

            filtered.forEach(item => {
                const div = document.createElement('div');
                div.textContent = item.nama_barang;
                div.classList.add('dropdown-item');
                div.style.padding = '8px';
                div.style.cursor = 'pointer';
                div.addEventListener('click', () => {
                    selected.push(item);
                    updateSelectedItems();
                    searchInput.value = '';
                    dropdownList.style.display = 'none';
                });
                dropdownList.appendChild(div);
            });

            dropdownList.style.display = 'block';
        });

        function updateSelectedItems() {
            selectedItems.innerHTML = '';
            selected.forEach((item, index) => {
                const tag = document.createElement('div');
                tag.className = 'selected-item d-inline-block bg-secondary text-white rounded px-2 py-1 me-2 mb-2';
                tag.innerHTML = `
                ${item.nama_barang}
                <button type="button" class="btn-close btn-close-white btn-lg ms-2" aria-label="Close"></button>
            `;

                tag.querySelector('button').addEventListener('click', () => {
                    selected.splice(index, 1);
                    updateSelectedItems();
                });

                selectedItems.appendChild(tag);
            });

            // Kirim hanya id_barang ke backend
            const ids = selected.map(item => item.id_barang);
            barangInput.value = JSON.stringify(ids);
        }

        // Hide dropdown when click outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.form-control') && !e.target.closest('.dropdown-list')) {
                dropdownList.style.display = 'none';
            }
        });
    </script>
    <script>
        const submitButton = document.getElementById('submitButton');

        function toggleSubmitButton() {
            submitButton.disabled = selected.length === 0;
        }

        // Call toggleSubmitButton whenever selected items are updated
        updateSelectedItems = (function(originalFunction) {
            return function() {
                originalFunction.apply(this, arguments);
                toggleSubmitButton();
            };
        })(updateSelectedItems);

        // Initial check
        toggleSubmitButton();
    </script>




</x-layout>