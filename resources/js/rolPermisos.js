import $ from "jquery";
import DataTable from "datatables.net-dt";

let tableRoles;
let tablePermisos;

document.addEventListener("DOMContentLoaded", async () => {
    cargarTabla();
    cargarTablaPermisos();
});

const obtenerRolPorId = async (id) => {
    const response = await fetch(`/api/rol/${id}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
        },
    });

    const data = await response.json();

    return data;
};

const editarRol = async (id, datos) => {
    const response = await fetch(`/api/rol/${id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
        },
        body: JSON.stringify(datos),
    });

    const data = await response.json();

    console.log(data);
};

// cargarRoles is no longer needed as DataTables handles ajax loading directly.

const cargarTabla = () => {
    var lang = {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix: "",
        sSearch: "Buscar:",
        sUrl: "",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
        },
        oAria: {
            sSortAscending:
                ": Activar para ordenar la columna de manera ascendente",
            sSortDescending:
                ": Activar para ordenar la columna de manera descendente",
        },
        buttons: {
            copy: "Copiar",
            colvis: "Visibilidad",
        },
    };
    $(document).ready(function () {
        //Enlazando tabla con datos AJAX
        tableRoles = $("#tablaRoles").DataTable({
            language: lang,
            ajax: {
                url: "/api/rol",
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                },
            },
            retrieve: true,
            dom: '<"d-flex justify-content-between align-items-center flex-wrap px-2 pt-2"lf>t<"d-flex justify-content-between align-items-center flex-wrap p-2"ip>',
            initComplete: function () {
                // Adaptamos DataTables 2 a Bootstrap 4 (AdminLTE)
                $(".dt-input").addClass("form-control form-control-sm d-inline-block mx-1");
                $("select.dt-input").addClass("custom-select custom-select-sm").css({ "width": "auto", "min-width": "65px", "padding-right": "24px" });
            },
            columns: [
                {
                    data: "id",
                },
                {
                    data: "nombre",
                },
                {
                    data: "descripcion",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return (
                            '<a class="btn btn-sm btn-success btnEdit" style="margin-right: 5px; cursor: pointer;"><i class="fa fa-edit"></i></a>' +
                            '<a class="btn btn-sm btn-danger btnDelete" style="margin-right: 5px; cursor: pointer;"><i class="fa fa-trash"></i></a>' +
                            '<a class="btn btn-sm btn-info btnPermisos" style="cursor: pointer;"><i class="fa fa-key"></i></a>'
                        );
                    },
                },
            ],
        });

        // Enlazando botones de la tabla
        $("#tablaRoles").on("click", ".btnPermisos", function () {
            var data = tableRoles.row($(this).closest("tr")).data();
            if (data) abrirModalPermisos(data["id"]);
        });
        $("#tablaRoles").on("click", ".btnEdit", function () {
            var data = tableRoles.row($(this).closest("tr")).data();
            if (data) abrirModalEditar(data["id"]);
        });

        $("#tablaRoles").on("click", ".btnDelete", async function () {
            var data = tableRoles.row($(this).closest("tr")).data();
            if (confirm("¿Está seguro de borrar el rol?")) {
                await borrarRol(data["id"]);
                tableRoles.ajax.reload();
            }
        });
    });
};

const abrirModalEditar = async (id) => {
    window.$("#modalEditarRol").modal("show");

    const datosBase = await obtenerRolPorId(id);
    document.querySelector("#edit_rol_nombre").value = datosBase.nombre;
    document.querySelector("#edit_rol_descripcion").value =
        datosBase.descripcion;

    window
        .$("#btnActualizarRol")
        .off("click")
        .on("click", async () => {
            const nombreRol = document.querySelector("#edit_rol_nombre").value;
            const descripcionRol = document.querySelector(
                "#edit_rol_descripcion",
            ).value;

            const datos = {
                nombre: nombreRol,
                descripcion: descripcionRol,
            };

            await editarRol(id, datos);
            if (tableRoles) tableRoles.ajax.reload();
            window.$("#modalEditarRol").modal("hide");
        });
};

const btnAgregarRol = document.querySelector("#btnGuardarRol");
btnAgregarRol.addEventListener("click", async () => {
    const nombreRol = document.querySelector("#add_rol_nombre").value;
    const descripcionRol = document.querySelector("#add_rol_descripcion").value;

    const datos = {
        nombre: nombreRol,
        descripcion: descripcionRol,
    };

    const response = await fetch("/api/rol", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
        },
        body: JSON.stringify(datos),
    });

    const data = await response.json();

    console.log(data);

    if (tableRoles) tableRoles.ajax.reload();
    window.$("#modalAgregarRol").modal("hide");
});

const borrarRol = async (id) => {
    const response = await fetch(`/api/rol/${id}`, {
        method: "DELETE",
        headers: {
            Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
        },
    });

    const data = await response.json();

    console.log(data);
};

////////PERMISOS////////

const cargarTablaPermisos = () => {
    var lang = {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix: "",
        sSearch: "Buscar:",
        sUrl: "",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
        },
        oAria: {
            sSortAscending:
                ": Activar para ordenar la columna de manera ascendente",
            sSortDescending:
                ": Activar para ordenar la columna de manera descendente",
        },
        buttons: {
            copy: "Copiar",
            colvis: "Visibilidad",
        },
    };
    $(document).ready(function () {
        //Enlazando tabla con datos AJAX
        tablePermisos = $("#tablaPermisos").DataTable({
            language: lang,
            ajax: {
                url: "/api/permiso",
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                },
            },
            dom: '<"d-flex justify-content-between align-items-center flex-wrap px-2 pt-2"lf>t<"d-flex justify-content-between align-items-center flex-wrap p-2"ip>',
            initComplete: function () {
                $(".dataTables_length select")
                    .addClass(
                        "custom-select custom-select-sm d-inline-block mx-1",
                    )
                    .css("width", "auto");
                $(".dataTables_filter input")
                    .addClass(
                        "form-control form-control-sm d-inline-block ml-1",
                    )
                    .css("width", "auto");
            },
            columns: [
                {
                    data: "id",
                },
                {
                    data: "tipo_permiso",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return '<a class="btn btn-sm btn-success btnEditPermiso" style="margin-right: 5px; cursor: pointer;"><i class="fa fa-key"></i></a>';
                    },
                },
            ],
        });

        // Enlazando botones de la tabla
        $("#tablaPermisos").on("click", ".btnEditPermiso", function () {
            var data = tablePermisos.row($(this).closest("tr")).data();
            if (data) abrirModalPermisos(data["id"]);
        });
    });
};

const abrirModalPermisos = async (id) => {
    window.$("#modalPermisos").modal("show");

    const datosBase = await obtenerRolPorId(id);
    document.querySelector("#nombreRolAsignar").innerText = datosBase.nombre;
    document.querySelector("#asignar_rol_id").value = id;

    // Obtener los permisos que ya tiene el rol
    const activePermisos = datosBase.permisos.map((p) => p.id_permiso);
    document.querySelectorAll(".checkbox-permiso").forEach((cb) => {
        cb.checked = activePermisos.includes(parseInt(cb.value));
    });

    window
        .$("#btnGuardarPermisosRol")
        .off("click")
        .on("click", async () => {
            const checkedPermisos = [];
            document
                .querySelectorAll(".checkbox-permiso:checked")
                .forEach((cb) => {
                    checkedPermisos.push(parseInt(cb.value));
                });

            const datos = {
                id_rol: id,
                id_permisos: checkedPermisos,
            };

            const response = await fetch("/api/asociarPermisoRol", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${localStorage.getItem("AuthToken")}`,
                },
                body: JSON.stringify(datos),
            });

            const data = await response.json();
            console.log(data);
            window.$("#modalPermisos").modal("hide");
        });
};
