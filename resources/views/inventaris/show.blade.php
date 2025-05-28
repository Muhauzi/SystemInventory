<x-layout>

    <x-slot name="title">
        Detail Barang
    </x-slot>

    <div class="pagetitle">
        <h1>Detail Barang Inventaris</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Kelola Inventaris</li>
                <li class="breadcrumb-item active">Detail Barang Inventaris</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="d-flex justify-content-end mb-3">
        @if (auth()->user()->role == 'admin')
        <a href="{{ route('inventaris.list' ,  $inventaris->id_kategori )}}">
            <button type="button" class="btn btn-secondary my-2 btn-icon-text">
                <i class="ri-arrow-go-back-fill"></i> Kembali
            </button>
        </a>
        @else
        <a href="{{ route('user.barangTersedia') }}">
            <button type="button" class="btn btn-secondary my-2 btn-icon-text">
                <i class="ri-arrow-go-back-fill"></i> Kembali
            </button>
        </a>
        @endif
    </div>
    <div class="card">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <h3>Gambar Barang</h3>
                    <div class="frame">
                        <img src="{{ asset('img/inventaris/' . $inventaris->foto_barang) }}" alt="Gambar" width="400px">
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Detail Barang</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th>Kode Barang</th>
                            <td>{{ $inventaris->id_barang }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>{{ $inventaris->nama_barang }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi & Spesifikasi Barang</th>
                            <td>{!! nl2br(e($inventaris->deskripsi_barang)) !!}</td>
                        </tr>
                        <tr>
                            <th>Status Barang</th>
                            <td>
                                @if ($inventaris->status_barang == 'Tersedia')
                                <span class="badge bg-success">{{ $inventaris->status_barang }}</span>
                                @elseif ($inventaris->status_barang == 'Dipinjam')
                                <span class="badge bg-info">{{ $inventaris->status_barang }}</span>
                                @elseif ($inventaris->status_barang == 'Dalam Perbaikan')
                                <span class="badge bg-warning">{{ $inventaris->status_barang }}</span>
                                @else
                                <span class="badge bg-danger">{{ $inventaris->status_barang }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kondisi</th>
                            <td>
                                @if ($inventaris->status_barang == 'Dalam Perbaikan')
                                <span class="badge bg-danger">Rusak</span>
                                @else
                                @if ($inventaris->kondisi == 'Baik')
                                <span class="badge bg-success">{{ $inventaris->kondisi }}</span>
                                @elseif ($inventaris->kondisi == 'Rusak' || $inventaris->kondisi == 'Hilang')
                                <span class="badge bg-danger">{{ $inventaris->kondisi }}</span>
                                @else
                                <span class="badge bg-warning">{{ $inventaris->kondisi }}</span>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @if (auth()->user()->role == 'admin')
                        <tr>
                            <th>Tanggal Pembelian</th>
                            <td>{{ $inventaris->tgl_pembelian }}</td>
                        </tr>
                        @endif


                        <tr>
                            <th>Harga Barang</th>
                            <td>Rp. {{ number_format($inventaris->harga_barang, 0, ',', '.') }}</td>
                        </tr>

                        <tr>
                            <th>Kategori</th>
                            <td>
                                @foreach ($kategori as $item)
                                @if ($item->id_kategori == $inventaris->id_kategori)
                                {{ $item->nama_kategori }}
                                @endif
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Jenis Barang</th>
                            <td>{{ $inventaris->jenis_barang }}</td>
                        </tr>

                    </table>

                    @if($data_peminjam != null && $data_peminjam->count() > 0 && auth()->user()->role == 'admin' && $inventaris->status_barang == 'Dipinjam')
                        <!-- Data Peminjam Table -->
                        <h3>Data Peminjam</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Peminjam</th>
                                <td>{{ $data_peminjam->first()->user->name ?? 'Tidak ada peminjam' }}</td>
                            </tr>
                            <tr>
                                <th>Status Peminjam</th>
                                <td>
                                    @php $role = $data_peminjam->first()->user->role ?? null; @endphp
                                    @if ($role == 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @elseif ($role == 'user')
                                        <span class="badge bg-secondary">Karyawan</span>
                                    @elseif ($role == 'partnership')
                                        <span class="badge bg-secondary">Partnership</span>
                                    @else
                                        <span class="badge bg-info">{{ $role }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Pinjam</th>
                                <td>{{ $data_peminjam->first()->tgl_pinjam ?? 'Tidak ada peminjaman' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Kembali</th>
                                <td>{{ $data_peminjam->first()->tgl_kembali ?? 'Belum dikembalikan' }}</td>
                            </tr>
                            <tr>
                                <th>Status Peminjaman</th>
                                <td>
                                    @php $status = $data_peminjam->first()->status ?? null; @endphp
                                    @if ($status == 'Dipinjam')
                                        <span class="badge bg-info">{{ $data_peminjam->first()->status }}</span>
                                    @elseif ($status == 'Selesai')
                                        <span class="badge bg-success">{{ $data_peminjam->first()->status }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $data_peminjam->first()->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        @if($role == 'partnership' && $data_peminjam->first()->penanggung_jawab)
                            <h3>Data Penanggung Jawab Peminjaman Partnership</h3>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nama Penanggung Jawab</th>
                                    <td>{{ $data_peminjam->first()->penanggung_jawab->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td>{{ $data_peminjam->first()->penanggung_jawab->jabatan }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <td>{{ $data_peminjam->first()->penanggung_jawab->no_hp }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $data_peminjam->first()->penanggung_jawab->email }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $data_peminjam->first()->penanggungJawab->alamat }}</td>
                                </tr>
                            </table>
                        @endif
                    @endif


                    @if (auth()->user()->role == 'admin')
                    <h3>QR Code</h3>
                    <div class="frame">
                        @if ($inventaris->qr_code)
                        <img src="{{ asset('img/qr/barang/' . $inventaris->qr_code) }}" alt="QR Code" width="200px">
                        <br>
                        <a href="{{ asset('img/qr/barang/' . $inventaris->qr_code) }}" download="{{ $inventaris->id_barang . ' | ' . $inventaris->nama_barang }}">
                            <button class="btn btn-info mt-2 text-white">
                                <i class="ri-download-2-fill"></i> Download QR Code
                            </button>
                        </a>
                        @else
                        <p>QR Code belum dibuat</p>
                        <a href="{{ route('inventaris.qr', $inventaris->id_barang) }}">
                            <button class="btn btn-primary mt-2 text-white">
                                <i class="ri-barcode-box-fill"></i> Generate QR Code
                            </button>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layout>