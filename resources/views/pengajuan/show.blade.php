<x-layout>
    <x-slot name="title">
        Data Pengajuan
    </x-slot>

    <div class="pagetitle">
        <h1>Detail Pengajuan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Pengajuan</li>
                <li class="breadcrumb-item active">Detail Pengajuan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <x-alert></x-alert>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-light">
                        <b>Data Pengajuan</b>
                    </div>
                    <div class="card-body mt-2 table-responsive">
                        <x-alert></x-alert>
                        <table class="table table-borderless">
                            <tr>
                                <td>Nama Peminjam</td>
                                <td>:</td>
                                <td>
                                    {{ $data['nama_peminjam'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Pinjam</td>
                                <td>:</td>
                                <td>
                                    {{ $data['tanggal_mulai'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Rencana Pengembalian</td>
                                <td>:</td>
                                <td>
                                    {{ $data['tanggal_selesai'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Diajukan</td>
                                <td>:</td>
                                <td>
                                    {{ $data['tanggal_pengajuan'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td>
                                    {{ $data['alasan'] }}
                                </td>
                            </tr>
                            <tr>
                                <td>Surat Pengantar</td>
                                <td>:</td>
                                <td>
                                    <!-- Download file surat pengantar pdf  -->
                                    <a href="{{ asset('surat_pengantar/' . $data['surat_pengantar']) }}" target="_blank" download>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            Download
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    @if ($data['status_pengajuan'] == 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif ($data['status_pengajuan'] == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                    @elseif ($data['status_pengajuan'] == 'Ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @else
                                    <span class="badge bg-info">{{ $data['status_pengajuan'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan Status</td>
                                <td>:</td>
                                <td>
                                    @if ($data['keterangan_pengajuan'] == null)
                                    <span class="badge bg-danger">Belum ada keterangan</span>
                                    @else
                                    {{ $data['keterangan_pengajuan'] }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Status Pengambilan</td>
                                <td>:</td>
                                <td>
                                    @if ($data['is_processed'] == false)
                                    <span class="badge bg-danger">Belum diambil</span>
                                    @else
                                    <span class="badge bg-success">Sudah diambil</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Barang</td>
                                <td>:</td>
                                <td>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Kode Barang</th>
                                                <th>Nama Barang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['barang'] as $brg)
                                            <tr>
                                                <th scope="row">{{ $brg['id_barang'] }}</th>
                                                <td>{{ $brg['nama_barang'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @if($data['status_pengajuan'] == 'Pending')
                        <div class="d-flex justify-content-end mt-4">
                            <x-form :action="route('pengajuan.updateStatus', ['id' => $data['id_pengajuan']])">
                                <label for="status_pengajuan" class="form-label"><b>Status Pengajuan</b></label>
                                <select name="status_pengajuan" class="form-select">
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                                <label for="keterangan_pengajuan" class="form-label mt-2"><b>Keterangan</b></label>
                                <textarea name="keterangan_pengajuan" class="form-control mt-2 mb-2" rows="3" placeholder="Keterangan"></textarea>
                                <x-slot name="customButton">
                                    <button type="submit" class="btn btn-primary mx-2 float-end">
                                        <i class="ri-check-line"></i> Update Status
                                    </button>
                                </x-slot>
                            </x-form>
                        </div>
                        @elseif($data['status_pengajuan'] == 'Disetujui' && $data['is_processed'] == 0)
                        <!-- Button trigger modal -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-success mx-2" data-bs-toggle="modal" data-bs-target="#prosesPeminjamanModal">
                                Proses Peminjaman
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="prosesPeminjamanModal" tabindex="-1" aria-labelledby="prosesPeminjamanModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('pengajuan.pengajuanDiambil', ['id' => $data['id_pengajuan']]) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="prosesPeminjamanModalLabel">Data Pengambil Barang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nama_pengambil" class="form-label">Nama Pengambil</label>
                                                <input type="text" class="form-control" id="nama_pengambil" name="nama_pengambil" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jabatan" class="form-label">Jabatan</label>
                                                <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="no_hp" class="form-label">Nomor HP</label>
                                                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat Tinggal</label>
                                                <textarea class="form-control" id="alamat" name="alamat" rows="2" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success">Proses Peminjaman</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ url()->previous() }}">
                                <button type="button" class="btn btn-secondary my-2 btn-icon-text">
                                    <i class="ri-arrow-go-back-line"></i> Kembali
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>