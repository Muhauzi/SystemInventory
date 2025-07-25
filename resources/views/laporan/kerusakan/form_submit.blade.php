<x-layout>

    <x-slot name="title">
        Form Laporan Kerusakan
    </x-slot>

    <div class="pagetitle">
        <h1>Form Laporan Kerusakan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Laporan Kerusakan</li>
                <li class="breadcrumb-item active">Form Laporan Kerusakan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section>
        <div class="container mx-auto px-4 sm:px-8">
            <div class="py-8">
                <div class="my-2 flex sm:flex-row flex-col">
                    <div class="block relative">
                        <div class="table-responsive">
                            @foreach ($dataPeminjam as $data)
                            <div class="card">
                                <div class="card-header bg-primary text-light">
                                    <b>
                                        Form Laporan Kerusakan
                                    </b>
                                </div>
                                <div class="card-body mt-2">
                                    <x-alert></x-alert>
                                    <form action="{{ route('laporan_kerusakan.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id_detail_peminjaman" value="{{ $data->id }}">
                                        <input type="hidden" name="id_barang" value="{{ $data->id_barang }}">
                                        <div class="mb-3">
                                            <label for="id_barang" class="form-label">Nama Barang</label>
                                            <input type="text" class="form-control" id="id_barang" name="id_barang"
                                                value="{{ $data->nama_barang }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label">Keterangan</label>
                                            <textarea class="form-control" id="keterangan" name="deskripsi_kerusakan" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto" class="form-label">Bukti Kerusakan Barang</label>
                                            <input class="form-control" type="file" id="foto" name="foto_kerusakan[]" multiple accept="image/*" >
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
    </section>
</x-layout>
