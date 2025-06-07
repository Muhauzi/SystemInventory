<x-layout>
    <x-slot name="title">
        Unduh Laporan Transaksi
    </x-slot>

    <div class="pagetitle">
        <h1>Unduh Laporan Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Laporan</li>
                <li class="breadcrumb-item active">Unduh Laporan Transaksi</li>
            </ol>
        </nav>
    </div><x-section>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ url()->previous() }}">
                <button type="button" class="btn btn-primary my-2 btn-icon-text">
                    <i class="ri-arrow-go-back-fill"></i> Kembali
                </button>
            </a>
        </div>

        <div class="m-4">
            <x-alert />
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Unduh Laporan</h5>

                <form id="form-laporan" class="row g-3" method="POST" action="{{ route('download.laporan_transaksi') }}">
                    @csrf

                    <div class="col-md-6">
                        <label for="jangka_waktu" class="form-label">Jangka Waktu</label>
                        <select id="jangka_waktu" class="form-select" name="jangka_waktu" required>
                            <option value="" selected>Pilih Jangka Waktu...</option>
                            <option value="1">Tahunan</option>
                            <option value="2">Bulanan</option>
                        </select>
                    </div>

                    <div class="col-md-6 tahunan" style="display: none;">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select id="tahun" class="form-select" name="tahun">
                            <option value="" selected>Pilih Tahun...</option>
                            @for ($i = date('Y'); $i >= 2021; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-6 bulanan" style="display: none;">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select id="bulan" class="form-select" name="bulan">
                            <option value="" selected>Pilih Bulan...</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-download-2-fill"></i> Unduh Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="preview-laporan" class="card mt-4" style="display: none;">
            <div class="card-body">
                <h5 class="card-title">Preview Data Laporan Transaksi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table-laporan">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tgl Pinjam</th>
                                <th>Peminjam</th>
                                <th>Nama Barang</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-confirm-modal />
    </x-section>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-laporan');
        const jangkaWaktu = document.getElementById('jangka_waktu');
        const tahunSelect = document.getElementById('tahun');
        const bulanSelect = document.getElementById('bulan');
        const tahunanSection = document.querySelector('.tahunan');
        const bulananSection = document.querySelector('.bulanan');
        const previewContainer = document.getElementById('preview-laporan');
        const previewTbody = document.querySelector('#table-laporan tbody');

        function toggleFields() {
            tahunanSection.style.display = 'none';
            bulananSection.style.display = 'none';
            if (jangkaWaktu.value === '1') tahunanSection.style.display = 'block';
            else if (jangkaWaktu.value === '2') bulananSection.style.display = 'block';
            fetchPreviewData();
        }

        function fetchPreviewData() {
            const jw = jangkaWaktu.value;
            const tahun = tahunSelect.value;
            const bulan = bulanSelect.value;
            
            let isValidForFetch = (jw === '1' && tahun) || (jw === '2' && bulan);

            if (!isValidForFetch) {
                previewContainer.style.display = 'none';
                previewTbody.innerHTML = '';
                return;
            }
            
            let query = `?jangka_waktu=${jw}`;
            if (jw === '1') query += `&tahun=${tahun}`;
            if (jw === '2') query += `&bulan=${bulan}`;

            previewTbody.innerHTML = '<tr><td colspan="7" class="text-center">Memuat data...</td></tr>';
            previewContainer.style.display = 'block';

            fetch(`{{ route('api.laporan_transaksi') }}${query}`)
                .then(response => response.json())
                .then(data => {
                    previewTbody.innerHTML = '';
                    if (!data || data.length === 0) {
                        previewTbody.innerHTML = '<tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>';
                        return;
                    }
                    
                    let no = 1;
                    data.forEach(item => {
                        const tglPinjam = new Date(item.tgl_pinjam).toLocaleDateString('id-ID');
                        const tglKembali = item.tgl_kembali ? new Date(item.tgl_kembali).toLocaleDateString('id-ID') : '-';
                        const denda = item.tagihan_denda && item.tagihan_denda.jumlah_tagihan > 0 
                            ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.tagihan_denda.jumlah_tagihan) 
                            : '-';
                        
                        let statusText = item.status;
                        let statusClass = 'bg-info';
                        if (item.status === 'Dikembalikan') {
                            statusClass = 'bg-success';
                        } else if (item.status === 'Dipinjam') {
                            statusClass = 'bg-warning';
                        }
                        
                        if (item.tgl_kembali && item.tgl_tenggat && new Date(item.tgl_kembali) > new Date(item.tgl_tenggat)) {
                            statusText = 'Terlambat';
                            statusClass = 'bg-danger';
                        }
                        
                        // Mengambil data dari nested object dengan aman
                        const namaPeminjam = item.user ? item.user.name : 'N/A';
                        const namaBarang = item.detail_peminjaman && item.detail_peminjaman.length > 0 && item.detail_peminjaman[0].barang
                            ? item.detail_peminjaman[0].barang.nama_barang
                            : 'N/A';
                        
                        const row = `
                            <tr>
                                <td>${no++}</td>
                                <td>${tglPinjam}</td>
                                <td>${namaPeminjam}</td>
                                <td>${namaBarang}</td>
                                <td>${tglKembali}</td>
                                <td><span class="badge ${statusClass}">${statusText}</span></td>
                                <td>${denda}</td>
                            </tr>`;
                        previewTbody.insertAdjacentHTML('beforeend', row);
                    });
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    previewContainer.style.display = 'block';
                    previewTbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data.</td></tr>';
                });
        }
        
        jangkaWaktu.addEventListener('change', toggleFields);
        tahunSelect.addEventListener('change', fetchPreviewData);
        bulanSelect.addEventListener('change', fetchPreviewData);

        form.addEventListener('submit', function(e) {
            if (jangkaWaktu.value === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Jangka Waktu harus diisi!',
                    timer: 2000
                });
            }
        });

        toggleFields(); 
    });
    </script>
</x-layout>