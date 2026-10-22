@php
    $mensaje = session('success');
@endphp

@if (session('success') || $errors->any())

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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