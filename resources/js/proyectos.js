import { Calendar } from "fullcalendar";
import interactionPlugin, { Draggable } from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";

// Variable global para el filtro del calendario por proyecto
let proyectoFiltradoId = null;
// Variable global para el filtro del calendario por usuario
let usuarioFiltradoId = null;

// Helper: obtener el token CSRF de la meta tag
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

document.addEventListener("DOMContentLoaded", async () => {
    // 1. Cargamos Proyectos e Inicializamos Draggables
    await cargarListaProyectos();

    // 2. Cargar usuarios en el select del calendario
    await cargarSelectUsuarios();

    // 2. Inicializamos FullCalendar
    const calendarEl = document.getElementById("calendarioProyectos");
    calendarEl.innerHTML = "";
    calendarEl.style.display = "block";
    calendarEl.style.minHeight = "auto";
    calendarEl.style.border = "none";
    calendarEl.style.background = "transparent";

    const calendar = new Calendar(calendarEl, {
        plugins: [timeGridPlugin, interactionPlugin],
        initialView: "timeGridWeek",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "timeGridWeek,timeGridDay",
        },
        locale: "es",
        height: "auto",
        allDaySlot: false,
        slotMinTime: "08:00:00",
        slotMaxTime: "19:00:00",
        slotDuration: "00:30:00",
        slotLabelInterval: "00:30", // Explicitly show axis labels every 30 minutes
        slotLabelContent: function (arg) {
            // El objeto arg.date tiene la fecha/hora. Extraemos horas y minutos.
            let hours = arg.date.getHours();
            let minutes = arg.date.getMinutes();
            // Si son minutos 0, devolvemos solo la hora
            if (minutes === 0) {
                return hours.toString();
            } else {
                // Si son :30, devolvemos hora:minutos
                return hours + ":" + minutes.toString().padStart(2, "0");
            }
        },
        droppable: true, // Permitir arrastrar cosas aquí

        // Cargar Tareas: filtra por proyecto y/o usuario si hay filtros activos
        events: async function (info, successCallback, failureCallback) {
            try {
                const res = await fetch("/api/tarea");
                const tareas = await res.json();

                const tareasFiltradas = tareas.filter((t) => {
                    const porProyecto = !proyectoFiltradoId || t.proyecto_id == proyectoFiltradoId;
                    const porUsuario = !usuarioFiltradoId || t.id_user == usuarioFiltradoId;
                    return porProyecto && porUsuario;
                });

                const eventos = tareasFiltradas.map((t) => ({
                    id: t.id,
                    title: t.proyecto ? t.proyecto.nombre : "Tarea",
                    start: t.tiempo_inicio,
                    end: t.tiempo_fin,
                    backgroundColor: "#28a745",
                    borderColor: "#1e7e34",
                    extendedProps: {
                        proyecto_id: t.proyecto_id,
                        proyecto_nombre: t.proyecto ? t.proyecto.nombre : "",
                        descripcion: t.descripcion,
                    },
                }));
                successCallback(eventos);
            } catch (error) {
                failureCallback(error);
            }
        },

        // Click en un evento: muestra las tareas de ese proyecto
        eventClick: async function (info) {
            const proyectoId = info.event.extendedProps.proyecto_id;
            const proyectoNombre = info.event.extendedProps.proyecto_nombre;

            document.getElementById(
                "modal_proyecto_nombre_titulo",
            ).textContent = proyectoNombre;
            document.getElementById("listaTareasModal").innerHTML =
                '<p class="text-muted text-center"><i class="fas fa-spinner fa-spin"></i> Cargando tareas...</p>';

            $("#modalVerTareas").modal("show");

            try {
                const res = await fetch("/api/tarea");
                const todasLasTareas = await res.json();
                const tareasDelProyecto = todasLasTareas.filter(
                    (t) => t.proyecto_id == proyectoId,
                );

                if (tareasDelProyecto.length === 0) {
                    document.getElementById("listaTareasModal").innerHTML =
                        '<p class="text-muted text-center">No hay tareas registradas para este proyecto.</p>';
                    return;
                }

                let html = '<ul class="list-group list-group-flush">';
                tareasDelProyecto.forEach((t) => {
                    const inicio = t.tiempo_inicio
                        ? new Date(t.tiempo_inicio).toLocaleString("es-ES", {
                              dateStyle: "short",
                              timeStyle: "short",
                          })
                        : "-";
                    const fin = t.tiempo_fin
                        ? new Date(t.tiempo_fin).toLocaleString("es-ES", {
                              dateStyle: "short",
                              timeStyle: "short",
                          })
                        : "-";
                    html += `<li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span class="badge badge-success mr-2 mt-1">${inicio}</span>
                            <span class="text-muted small"> &rarr; ${fin}</span>
                        </div>
                        ${t.descripcion ? '<p class="mb-0 mt-1 text-secondary">' + t.descripcion + "</p>" : ""}
                    </li>`;
                });
                html += "</ul>";

                document.getElementById("listaTareasModal").innerHTML = html;
            } catch (e) {
                document.getElementById("listaTareasModal").innerHTML =
                    '<p class="text-danger">Error al cargar las tareas.</p>';
            }
        },

        // Cuando soltamos un Draggable (Proyecto) dentro del calendario
        drop: function (info) {
            const proyectoId = info.draggedEl.getAttribute("data-id");
            const proyectoNombre = info.draggedEl.getAttribute("data-nombre");

            // Rellenamos los campos del modal de Tarea
            document.getElementById("t_proyecto_id").value = proyectoId;
            document.getElementById("t_proyecto_nombre").value = proyectoNombre;

            // Formateamos la fecha de la celda arrastrada con JS nativo
            const fechaInicio = info.date;
            const fechaFin = new Date(fechaInicio.getTime() + 30 * 60 * 1000); // +30 min

            document.getElementById("t_inicio").value =
                formatDateLocal(fechaInicio);
            document.getElementById("t_fin").value = formatDateLocal(fechaFin);
            document.getElementById("t_descripcion").value = "";

            // Guardamos el calendario en global para luego poder refetchEvents
            window.miCalendario = calendar;

            // Abrimos el modal de Bootstrap
            $("#modalCrearTarea").modal("show");
        },
    });

    calendar.render();
    // Guardar referencia global al calendario para usarla desde fuera del listener
    window.miCalendario = calendar;
});

// Helper: convierte Date a "YYYY-MM-DDTHH:mm" para input[type=datetime-local]
function formatDateLocal(date) {
    const pad = (n) => n.toString().padStart(2, "0");
    return (
        date.getFullYear() +
        "-" +
        pad(date.getMonth() + 1) +
        "-" +
        pad(date.getDate()) +
        "T" +
        pad(date.getHours()) +
        ":" +
        pad(date.getMinutes())
    );
}

// FUNCIÓN PARA CARGAR LA LISTA LATERAL DE PROYECTOS Y HACERLOS ARRASTRABLES
async function                    cargarListaProyectos() {
    try {
        const response = await fetch("/api/proyecto");
        const data = await response.json();

        const contenedor = document.getElementById(
            "listaProyectosArrastrables",
        );
        contenedor.innerHTML = "";


        if (data.length === 0) {
            contenedor.innerHTML =
                '<p class="text-muted text-center pt-3">No hay proyectos registrados aún.</p>';
        } else {
            data.forEach((p) => {
                // p.usuario_id viaja gracias a that Eloquent relationship
                const autor = p.usuario ? p.usuario.name : "Desconocido";

                const div = document.createElement("div");
                div.className =
                    "fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event proyecto-draggable bg-info border-info mb-2 p-2 rounded";
                div.setAttribute("data-id", p.id);
                div.setAttribute("data-nombre", p.nombre);

                div.innerHTML = `
                    <div class="fc-event-main text-white">
                        <strong>${p.nombre}</strong><br>
                        <small>Por: ${autor}</small>
                    </div>
                `;
                contenedor.appendChild(div);

                // Click en la tarjeta → filtrar el calendario a ese proyecto
                div.addEventListener("click", () => {
                    if (proyectoFiltradoId == p.id) {
                        // Segundo click en el mismo proyecto: quitar el filtro
                        proyectoFiltradoId = null;
                        // Quitar resaltado de todas las tarjetas
                        document
                            .querySelectorAll(".proyecto-draggable")
                            .forEach((el) => el.classList.remove("opacity-50", "border-warning"));
                        div.classList.remove("border-4", "border-white");
                    } else {
                        // Primer click: activar filtro
                        proyectoFiltradoId = p.id;
                        // Aplicar estilo visual: tarjeta activa brilla, el resto se atenúa
                        document
                            .querySelectorAll(".proyecto-draggable")
                            .forEach((el) => {
                                el.classList.add("opacity-50");
                                el.style.opacity = "0.45";
                            });
                        div.classList.remove("opacity-50");
                        div.style.opacity = "1";
                        div.style.outline = "3px solid white";
                    }

                    // Refrescar eventos del calendario con el nuevo filtro
                    if (window.miCalendario) {
                        window.miCalendario.refetchEvents();
                    }
                });
            });

            // Inicializar Draggable de FullCalendar para este contenedor
            new Draggable(contenedor, {
                itemSelector: ".proyecto-draggable",
                eventData: function (eventEl) {
                    return {
                        title: eventEl.getAttribute("data-nombre"),
                        create: false, // No crear el evento automáticamente, lo haremos con Modal
                    };
                },
            });
        }

        // Quitar el span de "cargando" si existe
        document.getElementById("listaProyectos").innerHTML = "";
    } catch (error) {
        console.error("Error al cargar proyectos", error);
    }
}

// GUARDAR NUEVO PROYECTO DESDE MODAL
const btnGuardarProyecto = document.getElementById("btnGuardarProyecto");
if (btnGuardarProyecto) {
    btnGuardarProyecto.addEventListener("click", async function () {
        const nombre = document.getElementById("p_nombre").value;
        try {
            const response = await fetch("/api/proyecto", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                },
                body: JSON.stringify({ nombre: nombre }),
            });

            if (response.ok) {
                $("#modalCrearProyecto").modal("hide");
                document.getElementById("formCrearProyecto").reset();
                // Recargar lista lateral
                await cargarListaProyectos();
            } else {
                alert("Error al guardar el proyecto.");
            }
        } catch (error) {
            console.error("API Error:", error);
        }
    });
}

// GUARDAR TAREA AL CAER EN EL CALENDARIO (DROP)
const btnGuardarTarea = document.getElementById("btnGuardarTarea");
if (btnGuardarTarea) {
    btnGuardarTarea.addEventListener("click", async function () {
        const tareaData = {
            proyecto_id: document.getElementById("t_proyecto_id").value,
            descripcion: document.getElementById("t_descripcion").value,
            tiempo_inicio: document.getElementById("t_inicio").value,
            tiempo_fin: document.getElementById("t_fin").value,
        };

        try {
            const response = await fetch("/api/tarea", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                },
                body: JSON.stringify(tareaData),
            });

            if (response.ok) {
                $("#modalCrearTarea").modal("hide");
                document.getElementById("formCrearTarea").reset();

                // Recargar eventos en el calendario
                if (window.miCalendario) {
                    window.miCalendario.refetchEvents();
                }
            } else {
                alert("Error al guardar la tarea.");
            }
        } catch (error) {
            console.error("API Error:", error);
        }
    });
}

// CARGAR USUARIOS EN EL SELECT DEL CALENDARIO
async function cargarSelectUsuarios() {
    try {
        const res = await fetch("/api/user");
        const usuarios = await res.json();

        const sel = document.getElementById("filtroUsuarioCalendario");
        if (!sel) {
            return;
        }

        sel.innerHTML = '<option value="">Todos los usuarios</option>';
        usuarios.forEach((u) => {
            const opt = document.createElement("option");
            opt.value = u.id;
            opt.textContent = u.name;
            sel.appendChild(opt);
        });

        sel.addEventListener("change", () => {
            usuarioFiltradoId = sel.value || null;
            if (window.miCalendario) {
                window.miCalendario.refetchEvents();
            }
        });
    } catch (e) {
        console.error("Error al cargar usuarios para el select:", e);
    }
}

const btnDescargar = document.querySelector("#btnDescargarPdf")

if (btnDescargar) {
    btnDescargar.addEventListener("click", () => {
        const usuario = document.querySelector("#pdf_usuario")
        const proyecto = document.querySelector("#pdf_proyecto")
        const fechaInicio = document.querySelector("#pdf_fecha_inicio")
        const fechaFin = document.querySelector("#pdf_fecha_fin")

        if (!usuario.value || !proyecto.value || !fechaInicio.value || !fechaFin.value) {
            alert("Por favor rellena todos los campos (Usuario, Proyecto y Fechas).");
            return;
        }

        const url = `/pdf/informe-tareas?user=${usuario.value}&proyecto=${proyecto.value}&fecha_inicio=${fechaInicio.value}&fecha_fin=${fechaFin.value}`;
        
        // Abrir PDF en una pestaña nueva
        window.open(url, "_blank");
        
        // Cerrar modal
        $("#modalGenerarPdf").modal("hide");
    });
}