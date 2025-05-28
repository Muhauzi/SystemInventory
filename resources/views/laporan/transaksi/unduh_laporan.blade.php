<x-layout>
    <x-slot name="title">
        Tambah Barang
    </x-slot>
    <div class="pagetitle">
        <h1>Unduh Laporan Transaksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Laporan Transaksi</li>
                <li class="breadcrumb-item active">Unduh Laporan Transaksi</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <x-section>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ url()->previous() }}">
                <button type="button" class="btn btn-primary my-2 btn-icon-text">
                    <i class="ri-arrow-go-back-fill"></i> Kembali
                </button>
            </a>
        </div>
        <div class="m-4">
            <x-alert></x-alert>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Form Unduh Laporan</h5>

                <!-- Multi Columns Form -->
                <form class="row g-3" method="post" action="{{ route('download.laporan_transaksi') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="col-md-6">
                        <label for="jangka_waktu" class="form-label">Jangka Waktu</label>
                        <select id="jangka_waktu" class="form-select" name="jangka_waktu" require>
                            <option selected value="">Pilih...</option>
                            <option value="1">Tahunan</option>
                            <option value="2">Bulanan</option>
                        </select>
                    </div>
                    <div class="col-md-6 tahunan" style="display: none;">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select id="tahun" class="form-select" name="tahun">
                            <option selected>Pilih...</option>
                            @for ($i = 2021; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-6 bulanan" style="display: none;">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select id="bulan" class="form-select" name="bulan">
                            <option selected>Pilih...</option>
                            @php
                                $date = new DateTime('now');
                            @endphp
                            @for ($i = 1; $i <= 12; $i++)
                                @if ($i <= $date->format('n'))
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                    </option>
                                @endif
                            @endfor
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" onclick="confirmModal()">
                            <i class="ri-download-2-fill"></i>
                            Unduh Laporan</button>
                    </div>
                </form><!-- End Multi Columns Form -->
            </div>
        </div>
        <x-confirm-modal></x-confirm-modal>
    </x-section>

    <script>
        // prevent submit if #jangka_waktu = ''
        document.querySelector('button[type="submit"]').addEventListener('click', function(e) {
            const jangkaWaktu = document.getElementById('jangka_waktu').value;
            if (jangkaWaktu === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Jangka Waktu harus diisi!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const jangkaWaktu = document.getElementById('jangka_waktu');
            const tahunan = document.querySelector('.tahunan');
            const bulanan = document.querySelector('.bulanan');

            jangkaWaktu.addEventListener('change', function() {
                const selectedValue = this.value;

                // Reset display
                tahunan.style.display = 'none';
                bulanan.style.display = 'none';

                // Show relevant fields based on selection
                if (selectedValue === '1') {
                    tahunan.style.display = 'block';
                } else if (selectedValue === '2') {
                    bulanan.style.display = 'block';
                }
            });
        });
    </script>
</x-layout>
