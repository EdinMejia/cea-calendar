import { Calendar } from '@fullcalendar/core';
import esLocale from '@fullcalendar/core/locales/es';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

const el = document.getElementById('calendario-cea');

if (el) {
    let eventos = [];
    try {
        eventos = JSON.parse(el.dataset.eventos || '[]').map((e) => ({
            ...e,
            // El punto y la lista usan este color: ahora toman el de la comisión
            borderColor: e.backgroundColor,
        }));
    } catch (err) {
        console.error('No se pudieron leer las actividades', err);
    }

    const buscador = document.getElementById('buscador-actividades');
    const compacto = () => window.innerWidth < 768;   // true = celular

    const limpiar = (t) => (t || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const mayuscula = (t) => t.charAt(0).toUpperCase() + t.slice(1);

    const filtrar = () => {
        const texto = limpiar(buscador ? buscador.value.trim() : '');
        return texto
            ? eventos.filter((e) => limpiar(e.extendedProps.busqueda).includes(texto))
            : eventos;
    };

    // Celular: flechas a los lados del título y botones de vista abajo
    const barraSuperior = () => compacto()
        ? { left: 'prev', center: 'title', right: 'next' }
        : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listMonth' };

    const barraInferior = () => compacto()
        ? { left: 'today', right: 'dayGridMonth,listMonth' }
        : false;

    let calendar;
    let eraCompacto = compacto();

    // Cambia las opciones que dependen del tamaño de pantalla
    const aplicarModo = () => {
        const c = compacto();
        el.classList.toggle('cea-compacto', c);
        calendar.setOption('headerToolbar', barraSuperior());
        calendar.setOption('footerToolbar', barraInferior());
        calendar.setOption('eventDisplay', c ? 'list-item' : 'block');  // punto o tarjetita
        calendar.setOption('dayMaxEvents', c ? false : 3);
    };

    // Ventana con los datos de UNA actividad
    const abrirDetalle = (ev) => {
        const p = ev.extendedProps;
        if (!window.Swal) {
            if (p.url) window.location.href = p.url;
            return;
        }
        Swal.fire({
            titleText: ev.title,
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
            confirmButtonText: 'Más...',
            cancelButtonText: 'Cerrar',
        }).then((r) => { if (r.isConfirmed && p.url) window.location.href = p.url; });
    };

    // Ventana con TODAS las actividades de un día (para el celular)
    const abrirDia = (fecha) => {
        if (!window.Swal) return;

        const lista = calendar.getEvents()
            .filter((ev) => ev.start && ev.start.toDateString() === fecha.toDateString())
            .sort((a, b) => a.start - b.start);
        if (!lista.length) return;

        const titulo = mayuscula(fecha.toLocaleDateString('es', { weekday: 'long', day: 'numeric', month: 'long' }));

        const items = lista.map((ev) => {
            const p = ev.extendedProps;
            return `<div style="border:2px solid #000;padding:8px 10px;background:${esc(ev.backgroundColor)};color:${esc(ev.textColor)}">
                <strong>${esc(ev.title)}</strong><br>
                <small>${esc(p.horario)}<br>${esc(p.lugar)}</small>
                ${p.url ? `<br><a href="${esc(p.url)}" style="color:inherit;font-weight:700;text-decoration:underline">Más...</a>` : ''}
            </div>`;
        }).join('');

        Swal.fire({
            titleText: titulo,
            html: `<div style="display:flex;flex-direction:column;gap:8px;text-align:left">${items}</div>`,
            showCloseButton: true,
            confirmButtonText: 'Cerrar',
        });
    };

    calendar = new Calendar(el, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        locales: [esLocale],
        locale: 'es',
        initialView: compacto() ? 'listMonth' : 'dayGridMonth',
        headerToolbar: barraSuperior(),
        footerToolbar: barraInferior(),
        buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', list: 'Lista' },
        height: 'auto',

        eventDisplay: compacto() ? 'list-item' : 'block',
        dayMaxEvents: compacto() ? false : 3,
        moreLinkClick: 'popover',
        moreLinkText: (n) => `+${n} más`,
        dayMaxEventRows: true,
        slotEventOverlap: false,
        eventMinHeight: 40,
        eventOverlap: false,

        nowIndicator: true,
        allDaySlot: false,
        slotMinTime: '00:00:00',     // así se ve también la actividad de las 12:00 AM
        slotMaxTime: '24:00:00',
        scrollTime: '07:00:00',      // la vista Semana abre cerca de las 7 AM
        eventTimeFormat: { hour: 'numeric', minute: '2-digit', hour12: true, meridiem: 'short' },
        noEventsText: 'No hay actividades en este período',

        events: (info, ok) => ok(filtrar()),

        // En la vista Lista agrega el lugar y la comisión debajo del título
        eventDidMount: (info) => {
            if (info.view.type !== 'listMonth') return;
            const p = info.event.extendedProps;
            const celda = info.el.querySelector('.fc-list-event-title');

            if (celda) {
                const linea = document.createElement('div');
                linea.className = 'mt-2 flex flex-wrap items-center gap-3';
                const htmlLugar = p.lugar ? `<span class="text-sm font-semibold text-gray-700">${esc(p.lugar)}</span>` : '';
                const htmlComision = p.comision ? `
                    <span style="background-color:${esc(info.event.backgroundColor)}; color:${esc(info.event.textColor)}"
                            class="border-2 border-black px-2 py-0.5 text-[10px] font-bold uppercase shadow-[1.5px_1.5px_0px_rgba(0,0,0,1)]">
                            ${esc(p.comision)}
                    </span>` : '';
                linea.innerHTML = `${htmlLugar} ${htmlComision}`;
                celda.appendChild(linea);
            }
        },

        eventClick: (info) => {
            info.jsEvent.preventDefault();
            if (compacto() && info.view.type === 'dayGridMonth') {
                abrirDia(info.event.start);
            } else {
                abrirDetalle(info.event);
            }
        },

        dateClick: (info) => {
            if (compacto() && info.view.type === 'dayGridMonth') abrirDia(info.date);
        },

        // Solo reacciona al cruzar el límite celular/computadora
        // (el teclado del celular también "redimensiona" y no debe cambiar las vistas)
        windowResize: () => {
            const ahora = compacto();
            if (ahora === eraCompacto) return;
            eraCompacto = ahora;
            aplicarModo();
            calendar.changeView(ahora ? 'listMonth' : 'dayGridMonth');
        },
    });

    el.classList.toggle('cea-compacto', compacto());
    calendar.render();

    if (buscador) {
        buscador.addEventListener('keydown', (e) => { if (e.key === 'Enter') e.preventDefault(); });
        buscador.addEventListener('input', () => calendar.refetchEvents());
    }
}