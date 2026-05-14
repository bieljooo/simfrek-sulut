<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('partials.flash-sweetalert')
<script>
    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        if (!form.dataset.sweetConfirm || form.dataset.sweetConfirmed === 'true' || !window.Swal) {
            return;
        }

        event.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: form.dataset.sweetTitle || 'Konfirmasi Aksi',
            text: form.dataset.sweetConfirm,
            showCancelButton: true,
            confirmButtonText: form.dataset.sweetConfirmButton || 'Lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#295fb7',
            cancelButtonColor: '#94a3b8',
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            form.dataset.sweetConfirmed = 'true';
            form.submit();
        });
    });
</script>