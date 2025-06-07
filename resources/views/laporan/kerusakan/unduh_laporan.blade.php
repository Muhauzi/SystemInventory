<x-layout>
    <x-slot name="title">
        Unduh Laporan Kerusakan
    </x-slot>

    <div class="pagetitle">
        <h1>Unduh Laporan Kerusakan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Laporan</li>
                <li class="breadcrumb-item active">Unduh Laporan Kerusakan</li>
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

                <form id="form-laporan" class="row g-3" method="POST" action="{{ route('download.laporan_kerusakan') }}">
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
                <h5 class="card-title">Preview Data yang Akan Diunduh</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table-laporan">
                        <thead class="table-light">
                            <tr>
                                <th>ID Laporan</th>
                                <th>Peminjam</th>
                                <th>Barang</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Tgl Laporan</th>
                                <th>Status Barang</th>
                                <th>Biaya Ganti Rugi</th>
                                <th>Status Pembayaran</th>
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
        // --- Referensi Elemen ---
        const form = document.getElementById('form-laporan');
        const jangkaWaktu = document.getElementById('jangka_waktu');
        const tahunSelect = document.getElementById('tahun');
        const bulanSelect = document.getElementById('bulan');
        const tahunanSection = document.querySelector('.tahunan');
        const bulananSection = document.querySelector('.bulanan');
        const previewContainer = document.getElementById('preview-laporan');
        const previewTbody = document.querySelector('#table-laporan tbody');

        // --- Fungsi untuk menampilkan/menyembunyikan field ---
        function toggleFields() {
            tahunanSection.style.display = 'none';
            bulananSection.style.display = 'none';

            if (jangkaWaktu.value === '1') {
                tahunanSection.style.display = 'block';
            } else if (jangkaWaktu.value === '2') {
                bulananSection.style.display = 'block';
            }
            fetchPreviewData();
        }

        // --- Fungsi untuk mengambil & menampilkan data preview ---
        function fetchPreviewData() {
            const jw = jangkaWaktu.value;
            const tahun = tahunSelect.value;
            const bulan = bulanSelect.value;
            
            // Kondisi baru yang lebih ketat, hanya jalan jika value tidak kosong
            let isValidForFetch = (jw === '1' && tahun) || (jw === '2' && bulan);

            if (!isValidForFetch) {
                previewContainer.style.display = 'none';
                previewTbody.innerHTML = '';
                return;
            }
            
            let query = `?jangka_waktu=${jw}`;
            if (jw === '1') query += `&tahun=${tahun}`;
            if (jw === '2') query += `&bulan=${bulan}`;

            previewTbody.innerHTML = '<tr><td colspan="9" class="text-center">Memuat data...</td></tr>';
            previewContainer.style.display = 'block';

            fetch(`{{ route('api.laporan_kerusakan') }}${query}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    previewTbody.innerHTML = '';
                    if (!data || data.length === 0) {
                        previewTbody.innerHTML = '<tr><td colspan="9" class="text-center">Data tidak ditemukan untuk filter yang dipilih.</td></tr>';
                        return;
                    }
                    
                    data.forEach(item => {
                        let status_barang_text = 'Belum Diperbaiki';
                        if (item.status_barang === 'Baik') status_barang_text = 'Telah Diperbaiki';
                        else if (item.status_barang === 'Dalam Perbaikan') status_barang_text = 'Dalam Perbaikan';
                        
                        let status_pembayaran_text = 'Belum Lunas';
                        if (item.status_pembayaran === 'capture' || item.status_pembayaran === 'settlement') {
                            status_pembayaran_text = 'Lunas';
                        }

                        const biaya_ganti_rugi_text = item.biaya_ganti_rugi 
                            ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.biaya_ganti_rugi) 
                            : 'Belum Ditentukan';

                        const tanggal_laporan_text = new Date(item.tanggal_laporan).toLocaleDateString('id-ID', {
                            day: '2-digit', month: 'long', year: 'numeric'
                        });

                        const row = `
                            <tr>
                                <td>${item.id_laporan_kerusakan}</td>
                                <td>${item.nama_peminjam}</td>
                                <td>${item.nama_barang}</td>
                                <td>${item.kategori_barang}</td>
                                <td>${item.deskripsi_kerusakan}</td>
                                <td>${tanggal_laporan_text}</td>
                                <td>${status_barang_text}</td>
                                <td>${biaya_ganti_rugi_text}</td>
                                <td><span class="badge ${status_pembayaran_text === 'Lunas' ? 'bg-success' : 'bg-warning'}">${status_pembayaran_text}</span></td>
                            </tr>`;
                        previewTbody.insertAdjacentHTML('beforeend', row);
                    });
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    previewContainer.style.display = 'block';
                    previewTbody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Gagal memuat data. Periksa konsol (F12).</td></tr>';
                });
        }

        // --- Event Listeners ---
        jangkaWaktu.addEventListener('change', toggleFields);
        tahunSelect.addEventListener('change', fetchPreviewData);
        bulanSelect.addEventListener('change', fetchPreviewData);

        // Validasi form sebelum submit
        form.addEventListener('submit', function(e) {
            if (jangkaWaktu.value === '') {
                e.preventDefault(); // Mencegah form dikirim
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Jangka Waktu harus diisi terlebih dahulu!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
            // Anda bisa menambahkan validasi untuk tahun/bulan jika diperlukan
        });

        // Inisialisasi tampilan saat halaman dimuat
        toggleFields(); 
    });
    </script>
</x-layout>