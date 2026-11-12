import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('submit', (e) => {
    const form = e.target;
    
    // Si no es un formulario o no tiene el atributo data-confirmar, lo deja pasar normal
    if (!(form instanceof HTMLFormElement) || !('confirmar' in form.dataset)) return;
    
    e.preventDefault();
    
    // Extrae los datos del formulario o usa valores por defecto
    const { 
        titulo = '¡Atención!', 
        texto = '¿Estás seguro de realizar esta acción?', 
        boton = 'Sí, continuar', 
        color = '#b91c1c' 
    } = form.dataset;

    // Si por alguna razón SweetAlert no cargó, usa la alerta nativa del navegador
    if (!window.Swal) {
        window.confirm(texto || titulo) ? form.submit() : form.reset();
        return;
    }

    // Lanza el SweetAlert con tu diseño de bordes gruesos
    window.Swal.fire({
        title: titulo, 
        text: texto, 
        icon: 'warning',
        showCancelButton: true, 
        confirmButtonColor: color, 
        cancelButtonColor: '#6b7280',
        confirmButtonText: boton, 
        cancelButtonText: 'Cancelar',
        animation: false,
        background: '#ffffff',
        customClass: { popup: 'border-2 border-black shadow-[4px_4px_0px_rgba(0,0,0,1)] rounded-none' }
    }).then((r) => (r.isConfirmed ? form.submit() : form.reset()));
});
