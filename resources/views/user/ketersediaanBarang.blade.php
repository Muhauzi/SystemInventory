<x-layout>
  <x-slot name="title">
    Barang Tersedia
  </x-slot>


  <div class="pagetitle">
    <h1>List Ketersediaan Barang</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">User</li>
        <li class="breadcrumb-item active">List Ketersediaan Barang</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->



  <section class="section">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card recent-sales overflow-auto">

          <div class="card-body">

            <h5 class="card-title">Tabel Ketersediaan Barang</h5>
            <table class="table table-borderless datatable">
              <thead style="background-color: rgba(233, 239, 248, 0.5);">
                <tr>
                  <th scope="col">Kode Barang</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Kategori Barang</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($inventaris as $item)
                <tr>
                  <th scope="row">{{ $item->id_barang }}</th>
                  <td>{{ $item->nama_barang }}</td>
                  <td>
                    {{ $item->kategoriBarang->nama_kategori }}
                  </td>
                  <td>
                    <a href="{{ route('user.detailBarang', $item->id_barang) }}">
                      <button type="button" class="btn btn-info btn-sm" title="Detail">
                        <i class="ri-eye-fill"></i>
                      </button>
                    </a>
                  </td>
                </tr>
                @endforeach
                <!-- Tambahkan baris lainnya sesuai kebutuhan -->
              </tbody>
            </table>
          </div>
          <!-- End Default Table Example -->
        </div>
      </div>
    </div>
  </section>
  <script>
    function confirmDelete(id) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + id).submit();
        }
      })
    }
  </script>

</x-layout>