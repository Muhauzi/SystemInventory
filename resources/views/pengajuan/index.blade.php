<x-layout>

    <x-slot name="title">
        {{ $title ?? 'Data Pengajuan' }}
    </x-slot>

    <div class="pagetitle">
        <h1>List Pengajuan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengajuan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card recent-sales overflow-auto">

                    <div class="card-body">
                        <h5 class="card-title">Tabel Pengajuan Peminjaman</h5>
                        <!-- Small tables -->
                        <div class="table-responsive">
                            <table class="table table-borderless datatable table-hover">
                                <thead style="background-color: rgba(233, 239, 248, 0.5);">
                                    <tr>
                                        <th scope="col">Kode Pengajuan</th>
                                        <th scope="col">Nama Peminjam</th>
                                        <th scope="col">Tanggal Pinjam</th>
                                        <th scope="col">Tenggat Pengembalian</th>
                                        <th scope="col">Tanggal Pengajuan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuan as $item)
                                    <tr>
                                        <th scope="row">{{ $item->id_pengajuan }}</th>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->tanggal_mulai }}</td>
                                        <td>{{ $item->tanggal_selesai }}</td>
                                        <td>{{ $item->tanggal_pengajuan }}</td>
                                        <td>
                                            @if ($item->status_pengajuan == 'Pending')
                                            <span class="badge bg-warning p-2">Pending</span>
                                            @elseif ($item->status_pengajuan == 'Disetujui')
                                            <span class="badge bg-success p-2">Disetujui</span>
                                            @elseif ($item->status_pengajuan == 'Ditolak')
                                            <span class="badge bg-danger p-2">Ditolak</span>
                                            @else
                                            <span class="badge bg-info p-2">{{ $item->status_pengajuan }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pengajuan.show', $item->id_pengajuan) }}">
                                                <button type="button" class="btn btn-primary btn-sm">
                                                   <i class="bi bi-eye-fill"></i>
                                                </button>
                                            </a>
                                        </td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
    </section>
</x-layout>