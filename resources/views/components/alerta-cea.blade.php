@if (session()->has('success') || (isset($errors) && $errors->any()))
    <!-- CDN de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if (session()->has('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        </script>
    @elseif (isset($errors) && $errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Complete los campos correctamente',
                    text: 'Revise los campos marcados en rojo.',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif
@endif