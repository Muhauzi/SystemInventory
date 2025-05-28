<x-layout>
    <x-slot name="title">
        Laporan Kerusakan
    </x-slot>

    <div class="pagetitle">
        <h1>Tagihan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="route('dashboard')">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Tagihan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <!-- List tagihan yang dimiliki user -->
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h5 class="card-title">List Tagihan Kerusakan</h5>
                        <div class="my-2 flex sm:flex-row flex-col">
                            <div class="block relative">
                            <table class="table table-borderless datatable table-hover">
                                <thead style="background-color: rgba(233, 239, 248, 0.5);">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">ID Peminjaman</th>
                                        <th scope="col">Kerusakan</th>
                                        <th scope="col">Biaya Kerusakan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tagihan as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->laporan_kerusakan->detailPeminjaman->peminjaman->id_peminjaman }}</td>
                                        <td>{{ $item->laporan_kerusakan->deskripsi_kerusakan }}</td>
                                        <td>Rp{{ number_format($item->total_tagihan, 0, ',', '.') }} </td>
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
                                            <div class="payment-button">
                                                @if ($item->status == 'capture' || $item->status == 'settlement')
                                                <button type="button" class="btn btn-success btn-sm text-white" title="Tagihan Lunas">
                                                    <i class="bi bi-check-fill"></i>
                                                    Lunas
                                                </button>
                                                @elseif ($item->payment_url != null && $item->status_tagihan != 'expire')
                                                <a href="{{ $item->payment_url }}" target="_blank" class="btn btn-success btn-sm text-white" title="Bayar Tagihan">
                                                    <i class="bi bi-credit-card-fill"></i>
                                                    Bayar Tagihan
                                                </a>
                                                @elseif ($item->payment_url == null)
                                                <form action="{{ route('user.tagihan_kerusakan.bayar', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm text-white" title="Buat Link Pembayaran">
                                                        <i class="bi bi-credit-card-fill"></i>
                                                        Buat Link Pembayaran
                                                    </button>
                                                </form>
                                                @elseif($item->status_tagihan == 'expire')
                                                <form action="{{ route('user.tagihan_kerusakan.bayar', $item->id) }}" method="POST">
                                                    @csrf
                                                    @if($item->status_tagihan == 'expire')
                                                        <button type="submit" class="btn btn-info btn-sm text-white">
                                                        <i class="bi bi-arrow-clockwise"></i> Perbarui Link Pembayaran</button>
                                                    @else
                                                        <button type="submit" class="btn btn-info btn-sm text-white">
                                                        <i class="bi bi-credit-card-fill"></i>
                                                            Buat Link Pembayaran</button>
                                                    @endif
                                                </form>
                                                @else
                                                <button type="button" class="btn btn-danger btn-sm text-white" title="Tagihan Dibatalkan">
                                                    <i class="bi bi-times-fill"></i>
                                                    {{ $item->status }}
                                                </button>
                                                @endif
                                            </div>
                                            <div class="detail-button">
                                                <a href="{{ route('user.show.TagihanKerusakan', $item->id_laporan_kerusakan) }}" class="btn btn-info btn-sm text-white" title="Detail Kerusakan">
                                                    <i class="bi bi-eye-fill"></i>
                                                    Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>