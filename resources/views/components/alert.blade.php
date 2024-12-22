@if (session('success'))
<script>
    Swal.fire({
        position: "top-end",
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2500
    });
</script>

@elseif (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: "{{ session('error') }}",
        showConfirmButton: false,
        timer: 2500
    });
</script>

@elseif (session('warning'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Peringatan',
        text: "{{ session('warning') }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif
