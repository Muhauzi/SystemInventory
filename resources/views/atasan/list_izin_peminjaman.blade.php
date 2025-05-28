<x-layout>

    <x-slot name="title">
        {{
            $title ?? 'Konfirmasi Peminjaman'
        }}
    </x-slot>

    <x-pagetittle>
        {{
            $title ?? 'Konfirmasi Peminjaman'
        }}
    </x-pagetittle>

    <div class="pagetitle">
        <h1>List Konfirmasi Peminjaman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Menu Pimpinan</li>
                <li class="breadcrumb-item active">List Konfirmasi Peminjaman</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section>
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">
                <div class="card">
                    <div class="card-body table-responsive">
                        <h5 class="card-title">Tabel Izin Peminjaman</h5>
                        <table class="table table-responsive table-data table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Kode Peminjaman</th>
                                        <th scope="col">Nama Peminjam</th>
                                        <th scope="col">Tanggal Pinjam</th>
                                        <th scope="col">Tenggat Pengembalian</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataPeminjaman as $item)
                                    <tr>
                                        <th scope="row">{{ $item->id_peminjaman }}</th>
                                        <td>
                                            @foreach ($users as $user)
                                            @if ($user->id == $item->id_user)
                                            {{ $user->name }}
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $item->tgl_pinjam }}</td>
                                        <td>{{ $item->tgl_kembali ?? 'Belum Dikembalikan' }}</td>
                                        <td>
                                            <span class="badge bg-warning">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('pimpinan.detail_izin', $item->id_peminjaman) }}"
                                                class="btn btn-sm btn-info">    
                                                <i class="ri-eye-fill"></i>     
                                            </a>  
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>