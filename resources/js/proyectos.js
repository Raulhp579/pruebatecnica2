import { Calendar } from "fullcalendar";
import interactionPlugin, { Draggable } from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import $ from 'jquery';
import select2 from 'select2';
import 'select2/dist/css/select2.css';
select2($);






window.$ = window.jQuery = $;

// Variable global para el filtro del calendario por proyecto
let proyectoFiltradoId = null;
// Variable global para el filtro del calendario por usuario
let usuarioFiltradoId = null;

let prioridad = null;
const usuario = null;

// Helper: obtener el token CSRF de la meta tag
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || "";
}

const botonesAdmin = document.querySelector("#botonesAdmin")
const filtroDivUsuario = document.querySelector("#filtroUsuario")




document.addEventListener("DOMContentLoaded", async () => {
    $('.usuarios').select2();
    $('.prioridad').select2();

    botonesAdmin.style.display = "none"
    selectUsuarios.style.display = "none"
    filtroDivUsuario.style.display = "none"

    if (await getUserRol() == 1) {
        selectUsuarios.style.display = ""
        botonesAdmin.style.display = ""
        filtroDivUsuario.style.display=""

    }

    const response = await fetch("/api/userInfoRol", {
        headers: {
            Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
        },
    });

    const data = await response.json();

    console.log(data);
    // 1. Cargamos Proyectos e Inicializamos Draggables
    await cargarListaProyectos();

    // 2. Cargar usuarios en el select del calendario
    if (await getUserRol() == 1) {
        await cargarSelectUsuarios();
    }


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
                let res = null

                if (await getUserRol() == 1) {
                    res = await fetch("/api/tarea", {
                        headers: {
                            "X-CSRF-TOKEN": csrfToken(),
                            Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                        },
                    });
                } else if (await getUserRol() == 2) {
                    res = await fetch("/api/misTareas", {
                        headers: {
                            "X-CSRF-TOKEN": csrfToken(),
                            Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                        },
                    });
                }


                const tareas = await res.json();

                const tareasFiltradas = tareas.filter((t) => {
                    const porProyecto =
                        !proyectoFiltradoId ||
                        t.proyecto_id == proyectoFiltradoId;
                    const porUsuario =
                        !usuarioFiltradoId || t.id_user == usuarioFiltradoId;

                    const porPrioridad = !prioridad || t.prioridad == prioridad;
                    return porProyecto && porUsuario && porPrioridad;
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

        // Click en un evento: abrir modal para editar/eliminar esa tarea
        eventClick: async function (info) {
            const tareaId = info.event.id;
            const descripcion = info.event.extendedProps.descripcion || "";
            const proyectoNombre =
                info.event.extendedProps.proyecto_nombre || info.event.title;
            const inicio = info.event.start;
            const fin = info.event.end;

            document.getElementById("edit_t_id").value = tareaId;
            document.getElementById("edit_t_proyecto_id").value =
                info.event.extendedProps.proyecto_id || "";
            document.getElementById("edit_t_proyecto_nombre").value =
                proyectoNombre;
            document.getElementById("edit_t_descripcion").value = descripcion;
            document.getElementById("edit_t_inicio").value =
                formatDateLocal(inicio);
            document.getElementById("edit_t_fin").value = fin
                ? formatDateLocal(fin)
                : formatDateLocal(inicio);

            window.$("#modalEditarTarea").modal("show");
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
            window.$("#modalCrearTarea").modal("show");
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
async function cargarListaProyectos() {
    try {
        const response = await fetch("/api/proyecto", {
            headers: {
                "X-CSRF-TOKEN": csrfToken(),
                Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
            },
        });
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
                            .forEach((el) =>
                                el.classList.remove(
                                    "opacity-50",
                                    "border-warning",
                                ),
                            );
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
                    Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                },
                body: JSON.stringify({ nombre: nombre }),
            });

            if (response.ok) {
                window.$("#modalCrearProyecto").modal("hide");
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
            prioridad: document.querySelector("#t_prioridad").value,
            id_user: document.querySelector("#asignado").value,
        };

        console.log(tareaData);
        try {
            const response = await fetch("/api/tarea", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                    Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                },
                body: JSON.stringify(tareaData),
            });

            if (response.ok) {
                window.$("#modalCrearTarea").modal("hide");
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

// ACTUALIZAR TAREA DESDE MODAL
const btnActualizarTarea = document.getElementById("btnActualizarTarea");
if (btnActualizarTarea) {
    btnActualizarTarea.addEventListener("click", async function () {
        const id = document.getElementById("edit_t_id").value;
        const tareaData = {
            descripcion: document.getElementById("edit_t_descripcion").value,
            tiempo_inicio: document.getElementById("edit_t_inicio").value,
            tiempo_fin: document.getElementById("edit_t_fin").value,
            proyecto_id: document.getElementById("edit_t_proyecto_id").value,
        };
        try {
            const response = await fetch(`/api/tarea/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                    Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                },
                body: JSON.stringify(tareaData),
            });

            if (response.ok) {
                window.$("#modalEditarTarea").modal("hide");
                if (window.miCalendario) window.miCalendario.refetchEvents();
                document.getElementById("formEditarTarea").reset();
            } else {
                alert("Error al actualizar la tarea.");
            }
        } catch (error) {
            console.error("API Error:", error);
        }
    });
}

// ELIMINAR TAREA DESDE MODAL
const btnEliminarTarea = document.getElementById("btnEliminarTarea");
if (btnEliminarTarea) {
    btnEliminarTarea.addEventListener("click", async function () {
        if (!confirm("¿Estás seguro de que quieres eliminar esta tarea?"))
            return;

        const id = document.getElementById("edit_t_id").value;

        try {
            const response = await fetch(`/api/tarea/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken(),
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                },
            });

            if (response.ok) {
                window.$("#modalEditarTarea").modal("hide");
                if (window.miCalendario) window.miCalendario.refetchEvents();
            } else {
                alert("Error al eliminar la tarea.");
            }
        } catch (error) {
            console.error("API Error:", error);
        }
    });
}

// CARGAR USUARIOS EN EL SELECT DEL CALENDARIO
async function cargarSelectUsuarios() {
    try {
        const res = await fetch("/api/user", {
            headers: {
                "X-CSRF-TOKEN": csrfToken(),
                Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
            },
        });
        const usuarios = await res.json();

        const sel = document.getElementById("filtroUsuarioCalendario");
        if (!sel) {
            return;
        }

        usuarios.data.forEach((u) => {
            const opt = document.createElement("option");
            opt.value = u.id;
            opt.textContent = u.name;
            sel.appendChild(opt);
        });


        $(sel).off("change").on("change", () => {
            usuarioFiltradoId = $(sel).val() || null;
            if (window.miCalendario) {
                window.miCalendario.refetchEvents();
            }
        });

    } catch (e) {
        console.error("Error al cargar usuarios para el select:", e);
    }
}

const btnDescargar = document.querySelector("#btnDescargarPdf");

if (btnDescargar) {
    btnDescargar.addEventListener("click", async () => {
        const usuario = document.querySelector("#pdf_usuario");
        const proyecto = document.querySelector("#pdf_proyecto");
        const fechaInicio = document.querySelector("#pdf_fecha_inicio");
        const fechaFin = document.querySelector("#pdf_fecha_fin");
        const prioridad = document.querySelector("#prioridad");

        if (
            !usuario.value ||
            !proyecto.value ||
            !fechaInicio.value ||
            !fechaFin.value ||
            !prioridad.value
        ) {
            alert(
                "Por favor rellena todos los campos (Usuario, Proyecto y Fechas).",
            );
            return;
        }

        const url = `/api/pdf/informe-tareas?user=${usuario.value}&proyecto=${proyecto.value}&fecha_inicio=${fechaInicio.value}&fecha_fin=${fechaFin.value}&prioridad=${prioridad.value}`;

        try {
            const response = await fetch(url, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                },
            });

            if (!response.ok) {
                alert("Error al generar el PDF");
                return;
            }

            const blob = await response.blob();
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.style.display = "none";
            a.href = downloadUrl;
            a.download = "informe-tareas.pdf";

            document.body.appendChild(a);
            a.click();

            setTimeout(() => {
                window.URL.revokeObjectURL(downloadUrl);
                a.remove();
            }, 100);

        } catch (error) {
            console.error("Error downloading PDF:", error);
            alert("Error al generar el PDF");
        }

        // Cerrar modal
        window.$("#modalGenerarPdf").modal("hide");
    });
}

const selectPrioridad = document.querySelector("#filtroPrioridad");



$(selectPrioridad).on("change", function () {
    prioridad = $(this).val() || null;

    if (window.miCalendario) {
        window.miCalendario.refetchEvents();
    }
});


const selectUsuarios = document.querySelector("#filtroUsuarioCalendario")

const getUserRol = async () => {
    const response = await fetch("/api/userInfoRol", {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('AuthToken')}`
        }
    })

    const data = await response.json()

    return data
}

