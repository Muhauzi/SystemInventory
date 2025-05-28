<form class="confirm-form" action="{{ $action }}" method="POST">
    @csrf
    @method($method ?? 'POST')

    {{ $slot }}

    @if (isset($customButton))
        {{ $customButton }}
    @else
        <button type="submit" class="btn btn-primary">
            {{ $buttonText ?? 'Submit' }}
        </button>
    @endif
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.confirm-form');

        forms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, submit!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
