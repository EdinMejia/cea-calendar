@if (session('success') || session('error') || $errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: @js(session('success')),
                    showConfirmButton: false,
                    timer: 2000,
                    animation: false,
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: @js(session('error')),
                    confirmButtonText: 'Aceptar',
                    animation: false,
                });
            @else
                Swal.fire({
                    icon: 'warning',
                    title: 'Complete los campos correctamente',
                    text: 'Revise los campos marcados en rojo.',
                    confirmButtonText: 'Aceptar',
                    animation: false,
                });
            @endif
        });
    </script>
@endif