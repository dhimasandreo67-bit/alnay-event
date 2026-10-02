{{-- SweetAlert2 — CDN + Helper Global --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const SWAL_COLOR = '#c99a32';

    // ---------- TOAST (auto-hide) ----------
    window.toast = function (message, type = 'success') {
        const iconMap = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: iconMap[type] || 'info',
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
        });
    };

    // ---------- ALERT ----------
    window.swalAlert = function (message, type = 'info', title = null) {
        Swal.fire({
            icon: type,
            title: title || (type === 'error' ? 'Oops...' : 'Informasi'),
            text: message,
            confirmButtonColor: SWAL_COLOR,
            confirmButtonText: 'OK',
        });
    };

    // ---------- KONFIRMASI ----------
    window.konfirmasi = function (message, onConfirm, options = {}) {
        Swal.fire({
            icon: options.icon || 'warning',
            title: options.title || 'Yakin?',
            text: message,
            showCancelButton: true,
            confirmButtonText: options.confirmText || 'Ya, Lanjutkan',
            cancelButtonText: options.cancelText || 'Batal',
            confirmButtonColor: options.confirmColor || SWAL_COLOR,
            cancelButtonColor: '#9ca3af',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    };

    // ---------- LOADING ----------
    window.swalLoading = function (message = 'Memproses...') {
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => { Swal.showLoading(); },
        });
    };

    window.swalClose = function () { Swal.close(); };
</script>   