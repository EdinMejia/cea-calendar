import { Calendar } from '@fullcalendar/core';
import esLocale from '@fullcalendar/core/locales/es';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

const el = document.getElementById('calendario-cea');

if (el) {
    let eventos = [];
    try {
        eventos = JSON.parse(el.dataset.eventos || '[]');
    } catch (e) {
        console.error('No se pudieron leer las actividades', e);
    }

    const buscador = document.getElementById('buscador-actividades');
    const esMovil  = () => window.innerWidth < 640;

    const limpiar = (t) => (t || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    // Filtra por lo escrito en el buscador
    const filtrar = () => {
        const texto = limpiar(buscador ? buscador.value.trim() : '');
        return texto
            ? eventos.filter((e) => limpiar(e.extendedProps.busqueda).includes(texto))
            : eventos;
    };

    // Teléfono: barra simple arriba y botones de vista abajo
    const encabezado = () => esMovil()
        ? { left: 'prev,next', center: 'title', right: '' }
        : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listMonth' };

    const pie = () => esMovil()
        ? { left: 'today', right: 'dayGridMonth,listMonth' }
        : false;

    let eraMovil = esMovil();

    const calendar = new Calendar(el, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin],
        locales: [esLocale],
        locale: 'es',
        initialView: esMovil() ? 'listMonth' : 'dayGridMonth',
        headerToolbar: encabezado(),
        footerToolbar: pie(),
        buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', list: 'Lista' },
        height: 'auto',
        dayMaxEvents: 3,
        moreLinkText: (n) => `+${n} más`,
        nowIndicator: true,
        allDaySlot: false,
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
        noEventsText: 'No hay actividades en este período',

        events: (info, ok) => ok(filtrar()),

        eventClick: (info) => {
            info.jsEvent.preventDefault();
            const p = info.event.extendedProps;

            if (!window.Swal) {
                if (p.url) window.location.href = p.url;
                return;
            }

            Swal.fire({
                titleText: info.event.title,
                html: `<div style="text-align:left;line-height:1.6">
                    <p><strong>Fecha:</strong> ${esc(p.fecha)}</p>
                    <p><strong>Horario:</strong> ${esc(p.horario)}</p>
                    <p><strong>Lugar:</strong> ${esc(p.lugar)}</p>
                    <p><strong>Comisión:</strong> ${esc(p.comision)}</p>
                    ${p.descripcion ? `<p><strong>Descripción:</strong> ${esc(p.descripcion)}</p>` : ''}
                </div>`,
                showCloseButton: true,
                showConfirmButton: !!p.url,
                showCancelButton: !!p.url,
                confirmButtonText: 'Ver detalle',
                cancelButtonText: 'Cerrar',
            }).then((r) => {
                if (r.isConfirmed && p.url) window.location.href = p.url;
            });
        },

        // Solo reacciona al cruzar el límite teléfono/computadora
        // (así el teclado del teléfono, que también "redimensiona", no cambia la vista)
        windowResize: () => {
            const ahora = esMovil();
            if (ahora === eraMovil) return;
            eraMovil = ahora;
            calendar.setOption('headerToolbar', encabezado());
            calendar.setOption('footerToolbar', pie());
            calendar.changeView(ahora ? 'listMonth' : 'dayGridMonth');
        },
    });

    calendar.render();

    if (buscador) {
        buscador.addEventListener('keydown', (e) => { if (e.key === 'Enter') e.preventDefault(); });
        buscador.addEventListener('input', () => calendar.refetchEvents());
    }
}