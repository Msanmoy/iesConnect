import { Calendar } from "@fullcalendar/core"
import dayGridPlugin from "@fullcalendar/daygrid"
import timeGridPlugin from "@fullcalendar/timegrid"
import interactionPlugin from "@fullcalendar/interaction"
import bootstrap5Plugin from "@fullcalendar/bootstrap5"
import esLocale from "@fullcalendar/core/locales/es"

// Estilos necesarios
import "bootstrap/dist/css/bootstrap.min.css"
import "@fullcalendar/bootstrap5/index.js" // Plugin JS
import "../css/calendar.css" // Importamos nuestros estilos personalizados

document.addEventListener("DOMContentLoaded", () => {
    const calendarEl = document.getElementById("calendar")

    if (!calendarEl) return

    // Crear modal para mostrar detalles del evento
    createEventModal()

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, bootstrap5Plugin],
        themeSystem: "bootstrap5",
        locale: esLocale,
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek",
        },
        events: (fetchInfo, successCallback, failureCallback) => {
            const month = fetchInfo.start.getMonth() + 2
            const year = fetchInfo.start.getFullYear()

            fetch(`/calendario/eventos?mes=${month}&anio=${year}`)
                .then((response) => response.json())
                .then((data) => {
                    const eventos = data.map((evento) => ({
                        id: evento.id,
                        title: `${evento.tipo.toUpperCase()}: ${evento.titulo}`,
                        start: evento.fecha,
                        backgroundColor: evento.tipo === "tarea" ? "#3b82f6" : "#ef4444",
                        borderColor: "#ccc",
                        textColor: "#fff",
                        extendedProps: {
                            descripcion: evento.descripcion,
                            tipo: evento.tipo,
                        },
                    }))
                    successCallback(eventos)
                })
                .catch((error) => {
                    console.error("Error al cargar eventos:", error)
                    failureCallback(error)
                })
        },
        eventClick: (info) => {
            const { title, start, extendedProps } = info.event
            const fecha = new Date(start)
            const descripcion = extendedProps.descripcion || "Sin descripción"
            const tipo = extendedProps.tipo || "evento"

            // Mostrar modal con detalles del evento
            showEventModal({
                title,
                fecha: fecha.toLocaleDateString("es-ES", {
                    weekday: "long",
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                }),
                descripcion,
                tipo,
            })
        },
        // Opciones adicionales para mejorar la experiencia
        dayMaxEvents: true, // Limitar eventos visibles por día
        eventTimeFormat: {
            hour: "2-digit",
            minute: "2-digit",
            meridiem: false,
            hour12: false,
        },
        // Personalización de textos
        buttonText: {
            today: "Hoy",
            month: "Mes",
            week: "Semana",
        },
        // Altura adaptable
        height: "auto",
    })

    calendar.render()

    // Función para crear el modal de eventos
    function createEventModal() {
        const modalHTML = `
            <div class="calendar-event-modal" id="eventModal">
                <div class="calendar-event-modal-content">
                    <div class="calendar-event-modal-header">
                        <h5 class="calendar-event-modal-title" id="eventModalTitle"></h5>
                        <button type="button" class="calendar-event-modal-close" id="eventModalClose">&times;</button>
                    </div>
                    <div class="calendar-event-modal-body">
                        <div class="mb-3">
                            <span class="badge" id="eventModalType"></span>
                        </div>
                        <p><strong>Fecha:</strong> <span id="eventModalDate"></span></p>
                        <p id="eventModalDescription"></p>
                    </div>
                    <div class="calendar-event-modal-footer">
                        <button type="button" id="eventModalCloseBtn">Cerrar</button>
                    </div>
                </div>
            </div>
        `

        document.body.insertAdjacentHTML("beforeend", modalHTML)

        // Agregar eventos para cerrar el modal
        document.getElementById("eventModalClose").addEventListener("click", closeEventModal)
        document.getElementById("eventModalCloseBtn").addEventListener("click", closeEventModal)
        document.getElementById("eventModal").addEventListener("click", function (e) {
            if (e.target === this) {
                closeEventModal()
            }
        })
    }

    // Función para mostrar el modal con los detalles del evento
    function showEventModal(eventData) {
        const modal = document.getElementById("eventModal")
        const title = document.getElementById("eventModalTitle")
        const type = document.getElementById("eventModalType")
        const date = document.getElementById("eventModalDate")
        const description = document.getElementById("eventModalDescription")

        title.textContent = eventData.title
        date.textContent = eventData.fecha
        description.textContent = eventData.descripcion

        // Establecer el color del badge según el tipo
        type.textContent = eventData.tipo.toUpperCase()
        type.className = "badge"
        if (eventData.tipo === "tarea") {
            type.classList.add("bg-primary")
        } else {
            type.classList.add("bg-danger")
        }

        modal.classList.add("show")
    }

    // Función para cerrar el modal
    function closeEventModal() {
        const modal = document.getElementById("eventModal")
        modal.classList.remove("show")
    }
})
