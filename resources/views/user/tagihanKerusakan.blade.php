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
    <section>
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">
                <x-alert></x-alert>

                <div class="my-2 flex sm:flex-row flex-col">
                    <div class="block relative">
                        <div class="table-responsive">
                            <table class="table table-data text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Barang</th>
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
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->deskripsi_kerusakan }}</td>
                                        <td>Rp{{ number_format($item->total_tagihan, 0, ',', '.') }} </td>
                                        <td>
                                            @if ($item->status_tagihan == 'capture' || $item->status_tagihan == 'settlement')
                                            <span class="badge bg-success">Lunas</span>
                                            @elseif ($item->status_tagihan == 'pending')
                                            <span class="badge bg-warning">Menunggu Pembayaran</span>
                                            @else
                                            <span class="badge bg-danger">{{ $item->status_tagihan }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status_tagihan == 'capture' || $item->status_tagihan == 'settlement')
                                            <button type="button" class="btn btn-success btn-sm" title="Tagihan Lunas">
                                                <i class="fas fa-check"></i>
                                                Lunas
                                            </button>
                                            @elseif ($item->status_tagihan == 'pending')
                                            <a href="{{ $item->payment_url }}">
                                                <button type="button" class="btn btn-info btn-sm" title="Bayar Tagihan">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                    Bayar Tagihan
                                                </button>
                                            </a>
                                            @else
                                            <button type="button" class="btn btn-danger btn-sm" title="Tagihan Dibatalkan">
                                                <i class="fas fa-times"></i>
                                                $item->status_tagihan
                                            </button>
                                            @endif
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