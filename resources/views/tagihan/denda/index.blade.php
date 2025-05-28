<x-layout>

    <x-slot name="title">
        Data Tagihan Denda
    </x-slot>

    <div class="pagetitle">
        <h1>Menu Tagihan Denda Keterlambatan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="route('dashboard')">Home</a></li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item active">Data Tagihan Denda</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">List Tagihan & Denda</h5>
                        <div class="my-2 flex sm:flex-row flex-col">
                            <div class="block relative">
                                <div class="table-responsive">
                                    <table class="table table-borderless datatable table-hover">
                                        <thead style="background-color: rgba(233, 239, 248, 0.5);">
                                            <tr>
                                                <th scope="col">Id Peminjaman</th>
                                                <th scope="col">Tanggal Pinjam</th>
                                                <th scope="col">Tanggal Kembali</th>
                                                <th scope="col">Jenis Tagihan</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Status Link Pembayaran</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tagihan as $item)
                                            <tr>
                                                <td>{{ $item->id_peminjaman }}</td>
                                                <td>{{ $item->peminjaman->tgl_pinjam }}</td>
                                                <td>{{ $item->peminjaman->tgl_kembali }}</td>
                                                <td>{{ $item->jenis_tagihan }}</td>
                                                <td>
                                                    @if ($item->status_tagihan == 'capture' || $item->status_tagihan == 'settlement')
                                                    <span class="badge bg-success">Lunas</span>
                                                    @elseif ($item->status_tagihan == 'pending')
                                                    <span class="badge bg-warning">Menunggu Pembayaran</span>
                                                    @elseif($item->status_tagihan == 'not_found' || $item->status_tagihan == 'expire')
                                                    <span class="badge bg-danger">Belum Lunas</span>
                                                    @else
                                                    <span class="badge bg-danger">{{ $item->status_tagihan }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->status_tagihan == 'capture' || $item->status_tagihan == 'settlement')
                                                    <button type="button" class="btn btn-success" disabled>
                                                        <i class="bi bi-check-circle-fill"></i> Lunas
                                                    </button>
                                                    @elseif ($item->status_tagihan == 'pending')
                                                    <button type="button" class="btn btn-warning" disabled>
                                                        <i class="bi bi-hourglass-split"></i> Menunggu Pembayaran
                                                    </button>
                                                    @else
                                                        @if($item->status_tagihan == 'not_found')
                                                        <button type="button" class="btn btn-danger" disabled>
                                                            <i class="bi bi-x-circle"></i> Pembayaran Belum Dibuat
                                                        </button>
                                                        @elseif($item->status_tagihan == 'expire')
                                                        <button type="button" class="btn btn-danger" disabled>
                                                            <i class="bi bi-clock-history"></i> Pembayaran Kadaluarsa
                                                        </button>
                                                        @else
                                                        <button type="button" class="btn btn-danger" disabled>
                                                            <i class="bi bi-x-info"></i> {{$item->status_tagihan}}
                                                        </button>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('user.tagihan.show', $item->id) }}" class="btn btn-primary">Detail</a>
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
            </div>
        </div>
    </section>
</x-layout>