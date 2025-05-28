<x-layout>

    <x-slot name="title">
        {{ $title ?? 'Data Pengajuan Saya' }}
    </x-slot>

    <div class="pagetitle">
        <h1>List Pengajuan Saya</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Pengajuan Saya</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card recent-sales overflow-auto">

                    <div class="card-body">
                        <h5 class="card-title">Tabel Pengajuan Saya</h5>
                        <div class="d-flex justify-content-start mb-1">
                            <a href="{{ route('user.addPengajuan') }}">
                                <button type="button" class="btn btn-primary my-2 btn-icon-text">
                                    <i class="ri-add-fill"></i> Ajukan Peminjaman
                                </button>
                            </a>
                        </div>
                        <!-- Small tables -->
                        <div class="table-responsive">
                            <table class="table table-borderless datatable table-hover">
                                <thead style="background-color: rgba(233, 239, 248, 0.5);">
                                    <tr>
                                        <th scope="col">Kode Pengajuan</th>
                                        <th scope="col">Tanggal Pengajuan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengajuan as $item)
                                    @if ($item->id_user == auth()->id())
                                    <tr>
                                        <th scope="row">{{ $item->id_pengajuan }}</th>
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
                                            <div class="row">
                                                <div class="col-auto">
                                                    <a href="{{ route('user.showPengajuan', $item->id_pengajuan) }}">
                                                        <button type="button" class="btn btn-info btn-sm text-white d-flex align-items-center" title="Detail">
                                                            <i class="ri-eye-fill me-1"></i> <span>Detail Pengajuan</span>
                                                        </button>
                                                    </a>
                                                </div>
                                                @if ($item->status_pengajuan == 'pending')
                                                <div class="col-auto">
                                                    <form id="delete-form-{{ $item->id_pengajuan }}" action="{{ route('user.deletePengajuan', $item->id_pengajuan) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm text-white d-flex align-items-center" title="Hapus" onclick="confirmDelete('{{ $item->id_pengajuan }}')">
                                                            <i class="ri-delete-bin-5-fill me-1"></i> <span>Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- End Default Table Example -->
                    </div>
                </div>
            </div>
            <script>
                function confirmDelete(id) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
                        }
                    })
                }
            </script>
    </section>
</x-layout>