<x-layout>
    <x-slot name="title">
        Dashboard
    </x-slot>
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Barang Inventaris</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>
                                            {{ count($dataInventaris) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" id="filter-peminjaman-today" href="#">Today</a></li>
                                    <li><a class="dropdown-item" id="filter-peminjaman-month" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" id="filter-peminjaman-year" href="#">This Year</a></li>
                                </ul>
                            </div>

                            <div class="card-body" id="peminjaman-day">
                                <h5 class="card-title">Peminjaman <span>| This Day</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-arrow-down-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>
                                            <span id="peminjaman-day-count"></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body" id="peminjaman-month">
                                <h5 class="card-title">Peminjaman <span>| This Month</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-arrow-down-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>
                                            <span id="peminjaman-month-count"></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body" id="peminjaman-year">
                                <h5 class="card-title">Peminjaman <span>| This Year</span></h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-arrow-down-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>
                                            <span id="peminjaman-year-count"></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->

                    <!-- Customers Card -->
                    <div class="col-xxl-4 col-xl-12">

                        <div class="card info-card customers-card">

                            <div class="card-body">
                                <h5 class="card-title">Users</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>
                                            {{ count($dataUser) }}
                                        </h6>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Customers Card -->

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">
                                <h5 class="card-title">Ketersediaan barang</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID Barang</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataInventaris as $key => $inventaris)
                                        @if ($inventaris->status_barang == 'Tersedia')
                                        <tr>
                                            <th scope="row">{{ $inventaris->id_barang }}</th>
                                            <td>{{ $inventaris->nama_barang }}</td>
                                            <td>{{ $inventaris->nama_kategori }}</td>
                                            <td>Rp.{{ number_format($inventaris->harga_barang, 0, ',', '.') }}</td>
                                            <td><span class="badge bg-success">{{ $inventaris->status_barang }}</span></td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">
                                <h5 class="card-title">Barang Dipinjam</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID Barang</th>
                                            <th scope="col">Nama Peminjam</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($barangDipinjam as $key => $inventaris)
                                        <tr>
                                            <th scope="row">{{ $inventaris->id_barang }}</th>
                                            <td>{{ $inventaris->name }}</td>
                                            <td>{{ $inventaris->nama_barang }}</td>
                                            <td>{{ $inventaris->nama_kategori }}</td>
                                            <td>Rp.{{ number_format($inventaris->harga_barang, 0, ',', '.') }}</td>
                                            <td><span class="badge bg-success">
                                                @if ($inventaris->status_barang == 'Tidak Tersedia')
                                                    Dipinjam
                                                @endif
                                                </span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">
                                <h5 class="card-title">Barang Dikembalikan</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID Barang</th>
                                            <th scope="col">Nama Peminjam</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Tanggal Dikembalikan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($barangDikembalikan as $key => $inventaris)
                                        <tr>
                                            <th scope="row">{{ $inventaris->id_barang }}</th>
                                            <td>{{ $inventaris->name }}</td>
                                            <td>{{ $inventaris->nama_barang }}</td>
                                            <td>{{ $inventaris->nama_kategori }}</td>
                                            <td>Rp.{{ number_format($inventaris->harga_barang, 0, ',', '.') }}</td>
                                            <td><span class="badge bg-success">Dikembalikan</span></td>
                                            <td class="ps-5">{{ \Carbon\Carbon::parse($inventaris->updated_at_peminjaman)->format('d-m-Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">
                                <h5 class="card-title">Barang Rusak</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID Barang</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataInventaris as $key => $inventaris)
                                        @if ($inventaris->kondisi == 'Rusak')
                                        <tr>
                                            <th scope="row">{{ $key + 1 }}</th>
                                            <td>{{ $inventaris->nama_barang }}</td>
                                            <td>{{ $inventaris->nama_kategori }}</td>
                                            <td>Rp.{{ number_format($inventaris->harga_barang, 0, ',', '.') }}</td>
                                            <td><span class="badge bg-danger">{{ $inventaris->kondisi }}</span></td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">Aktifitias Peminjaman <span>| Today</span></h5>

                        <div class="activity">

                            <!-- <div class="activity-item d-flex">
                                <div class="activite-label">32 min</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">
                                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                                </div>
                            </div> -->

                            
                            @foreach ($dataPeminjaman->sortByDesc('updated_at_peminjaman') as $peminjaman)
                            @if (\Carbon\Carbon::parse($peminjaman->updated_at_peminjaman)->isToday())
                            <div class="activity-item d-flex">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 20%;" class="activite-label">
                                            <p class="me-5">{{ \Carbon\Carbon::parse($peminjaman->updated_at_peminjaman)->diffForHumans() }}</p>
                                        </td>
                                        <td style="width: 6%;">
                                            <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                        </td>
                                        <td style="width: 75%;">
                                            <div class="activity-content ms-2">
                                                @if ($peminjaman->status == 'Dipinjam')
                                                <a href="#" class="fw-bold text-dark">{{ $peminjaman->name }}</a>
                                                Meminjam
                                                @elseif ($peminjaman->status == 'Dikembalikan')
                                                <a href="#" class="fw-bold text-dark">{{ $peminjaman->name }}</a>
                                                Mengembalikan
                                                @elseif ($peminjaman->status == 'Ditolak')
                                                Peminjaman
                                                <a href="#" class="fw-bold text-dark">{{ $peminjaman->name }}</a> Ditolak
                                                @elseif ($peminjaman->status == 'Pending')
                                                Menunggu Konfirmasi Peminjaman
                                                @elseif ($peminjaman->status == 'Disetujui')
                                                Peminjaman
                                                <a href="#" class="fw-bold text-dark">{{ $peminjaman->name }}</a> Disetujui |
                                                @endif
                                                <a href="#" class="fw-bold text-dark">{{ $peminjaman->nama_barang }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div><!-- End activity item-->
                            @endif
                            @endforeach
                        </div>

                    </div>
                </div><!-- End Recent Activity -->

                <!-- Website Traffic -->
                <div class="card">

                    <div class="card-body">
                        <h5 class="card-title">Jumlah Barang By Kategori</h5>

                        <!-- Doughnut Chart -->
                        <canvas id="doughnutChart" style="max-height: 400px; display: block; box-sizing: border-box; height: 400px; width: 728px;" width="728" height="400"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                // Ambil data dari Blade (PHP)
                                const jumlahBarangByKategori = @json($jumlahBarangByKategori);

                                // Konversi data ke dalam format untuk Chart.js
                                const labels = Object.keys(jumlahBarangByKategori); // Mendapatkan kategori
                                const data = Object.values(jumlahBarangByKategori); // Mendapatkan jumlah barang

                                // Generate warna dinamis berdasarkan jumlah kategori
                                const generateRandomColor = () => {
                                    return `rgb(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)})`;
                                };

                                const backgroundColors = labels.map(() => generateRandomColor()); // Membuat warna secara dinamis

                                // Buat Doughnut Chart
                                new Chart(document.querySelector('#doughnutChart'), {
                                    type: 'doughnut',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Jumlah Barang per Kategori',
                                            data: data,
                                            backgroundColor: backgroundColors, // Warna yang di-generate otomatis
                                            hoverOffset: 4
                                        }]
                                    }
                                });
                            });
                        </script>
                        <!-- End Doughnut Chart -->

                    </div>
                </div><!-- End Website Traffic -->

            </div><!-- End Right side columns -->

        </div>
    </section>
    <script>
        const generateCategories = () => {
            const categories = [];
            const now = new Date();

            // Loop for the past 6 days, starting from today
            for (let i = 6; i >= 0; i--) {
                const date = new Date(now);
                date.setDate(now.getDate() - i);
                categories.push(date.toISOString());
            }

            return categories;
        };

        const dataInventaris = @json($dataInventaris);
        const dataPeminjaman = @json($dataPeminjaman);

        const dataInventarisCount = dataInventaris.map(item => {
            const createdAt = new Date(item.created_at_inventaris);
            return createdAt.getDate();
        });

        const dataPeminjamanCount = dataPeminjaman.map(item => {
            const createdAt = new Date(item.created_at_peminjaman);
            return createdAt.getDate();
        });

        // Filter peminjaman
        // Set today as default
        document.getElementById('peminjaman-day').style.display = 'block';
        document.getElementById('peminjaman-month').style.display = 'none';
        document.getElementById('peminjaman-year').style.display = 'none';

        document.getElementById('filter-peminjaman-today').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('peminjaman-day').style.display = 'block';
            document.getElementById('peminjaman-month').style.display = 'none';
            document.getElementById('peminjaman-year').style.display = 'none';
        });

        document.getElementById('filter-peminjaman-month').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('peminjaman-day').style.display = 'none';
            document.getElementById('peminjaman-month').style.display = 'block';
            document.getElementById('peminjaman-year').style.display = 'none';
        });

        document.getElementById('filter-peminjaman-year').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('peminjaman-day').style.display = 'none';
            document.getElementById('peminjaman-month').style.display = 'none';
            document.getElementById('peminjaman-year').style.display = 'block';
        });

        document.addEventListener("DOMContentLoaded", () => {
            const dataPeminjaman = @json($dataPeminjaman);
            const currentDay = new Date().getDate();
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();

            const countThisDay = dataPeminjaman.filter(item => {
                const createdAt = new Date(item.created_at_peminjaman);
                return createdAt.getDate() === currentDay && createdAt.getMonth() === currentMonth && createdAt.getFullYear() === currentYear;
            }).length;

            document.getElementById('peminjaman-day-count').innerText = countThisDay;
        });

        document.addEventListener("DOMContentLoaded", () => {
            const dataPeminjaman = @json($dataPeminjaman);
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();

            const countThisMonth = dataPeminjaman.filter(item => {
                const createdAt = new Date(item.created_at_peminjaman);
                return createdAt.getMonth() === currentMonth && createdAt.getFullYear() === currentYear;
            }).length;

            document.getElementById('peminjaman-month-count').innerText = countThisMonth;
        });

        document.addEventListener("DOMContentLoaded", () => {
            const dataPeminjaman = @json($dataPeminjaman);
            const currentYear = new Date().getFullYear();

            const countThisYear = dataPeminjaman.filter(item => {
                const createdAt = new Date(item.created_at_peminjaman);
                return createdAt.getFullYear() === currentYear;
            }).length;

            document.getElementById('peminjaman-year-count').innerText = countThisYear;
        });
    </script>
</x-layout>