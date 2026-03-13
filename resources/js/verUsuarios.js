import $ from "jquery";
import DataTable from "datatables.net-dt";

document.addEventListener("DOMContentLoaded", () => {
    cargarUsuarios();
});

const cargarTabla = (data) => {
    const lang = {
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

    data.forEach(usuario => {
        if(usuario.administrador == 1){
            usuario.administrador = "Es administrador"
        }else if(usuario.administrador == 2){
            usuario.administrador = "No es administrador"
        }
    });

    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable("#tablaUsuarios")) {
            $("#tablaUsuarios").DataTable().destroy();
            $("#tablaUsuarios").empty();
        }

        //Enlazando tabla con datos AJAX
        const table = $("#tablaUsuarios").DataTable({
            language: lang,
            data: data,
            columns: [
                {
                    data: "id",
                },
                {
                    data: "name",
                },
                {
                    data: "email",
                },
                {
                    data: "administrador",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return (
                            '<a class="btn btn-sm btn-success btnEdit" style="margin-right: 5px; cursor: pointer;"><i class="fa fa-edit"></i></a>' +
                            '<a class="btn btn-sm btn-danger btnDelete" style="cursor: pointer;"><i class="fa fa-trash"></i></a>'
                        );
                    },
                },
            ],
        });

        $("#tablaUsuarios")
            .off("click", ".btnEdit")
            .on("click", ".btnEdit", function () {
                const data = table.row($(this).closest("tr")).data();
                if (data) abrirModalEditar(data);
            });

        $("#tablaUsuarios")
            .off("click", ".btnDelete")
            .on("click", ".btnDelete", function () {
                const data = table.row($(this).closest("tr")).data();
                if (data) eliminarUsuario(data.id);
            });
    });
};

async function cargarUsuarios() {
    const response = await fetch("/api/user", {
        headers: {
            'Authorization':`Bearer ${localStorage.getItem("AuthToken")}`
        },
    });
    const data = await response.json();

    console.log(data.data);

    cargarTabla(data.data);
}

// Llenamos el modal con los datos del usuario correspondiente y lo mostramos
function abrirModalEditar(user) {
    document.getElementById("edit_id").value = user.id;
    document.getElementById("edit_name").value = user.name;
    document.getElementById("edit_email").value = user.email;
    document.getElementById("edit_password").value = ""; // La contraseña siempre en blanco por seguridad
    document.getElementById("edit_administrador").checked = user.administrador;

    // Usamos jQuery (que viene incluido en AdminLTE) para mostrar el modal de Bootstrap
    window.$("#modalEditarUsuario").modal("show");
}

// Evento para el botón de Guardar Cambios dentro del modal
document
    .getElementById("btnGuardarEdicion")
    .addEventListener("click", async () => {
        const id = document.getElementById("edit_id").value;

        const rol = document.getElementById("edit_administrador").checked

        const usuarioActualizado = {
            nombre: document.getElementById("edit_name").value,
            correo: document.getElementById("edit_email").value,
            esAdmin: rol? 1:2,
        };

        // Solo enviamos la contraseña si el usuario ha escrito algo nuevo
        const nuevaPassword = document.getElementById("edit_password").value;
        if (nuevaPassword.trim() !== "") {
            usuarioActualizado.contrasena = nuevaPassword;
        }

        try {
            const response = await fetch(`/api/user/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    'Authorization':`Bearer ${localStorage.getItem("AuthToken")}`
                },
                body: JSON.stringify(usuarioActualizado),
            });

            const data = await response.json();
            console.log("Respuesta al editar:", data);

            // Escondemos el modal y recargamos la tabla
            window.$("#modalEditarUsuario").modal("hide");
            cargarUsuarios();
        } catch (error) {
            console.error("Error al actualizar el usuario:", error);
        }
    });

async function eliminarUsuario(id) {
    try {
        const response = await fetch(`/api/user/${id}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                'Authorization':`Bearer ${localStorage.getItem("AuthToken")}`
            },
        });

        const data = await response.json();
        console.log("Respuesta al eliminar:", data);

        cargarUsuarios();
    } catch (error) {
        console.error("Error al eliminar el usuario:", error);
    }
}

const btnAñadirUsuario = document.querySelector("#btnGuardarUsuario");

btnAñadirUsuario.addEventListener("click", async () => {

    const rol = document.querySelector("#add_administrador").checked

    const usuarioAñadido = {
        nombre: document.getElementById("add_name").value,
        correo: document.getElementById("add_email").value,
        contrasena: document.getElementById("add_password").value,
        esAdmin: rol?1:2,
    };

    console.log(usuarioAñadido)

    try {
        const response = await fetch("/api/user", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                'Authorization':`Bearer ${localStorage.getItem("AuthToken")}`
            },
            body: JSON.stringify(usuarioAñadido),
        });

        const data = await response.json();
        console.log("Respuesta al añadir:", data);

        // Escondemos el modal y recargamos la tabla
        window.$("#modalAñadirUsuario").modal("hide");
        cargarUsuarios();
    } catch (error) {
        console.error("Error al añadir el usuario:", error);
    }
});



// filtroNombre.addEventListener("change", async () => {
//     const nombre = filtroNombre.value

//     cargarUsuarios(nombre)

// })

/*    try {



       var table = $('#example').DataTable({
           data: data,
           columns: [{
                   data: 'name'
               }, {
                   data: 'position'
               }, {
                   data: 'salary'
               }, {
                   data: 'office',
               },
               {
                   data: null,
                   render: function (data, type, row, meta) {
                       return '<a id="btnEdit" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>';
                   }
               }
           ],
       });

       const tbody = document.getElementById("tablaUsuariosBody");
       tbody.innerHTML = "";

       // Iteramos los usuarios devueltos
       data.forEach((user) => {
           const tr = document.createElement("tr");

           const adminBadge = user.administrador
               ? '<span class="badge bg-success">Sí</span>'
               : '<span class="badge bg-danger">No</span>';

           const fecha = user.created_at
               ? new Date(user.created_at).toLocaleDateString("es-ES")
               : "N/A";

           tr.innerHTML = `
               <td>${user.id}</td>
               <td>${user.name}</td>
               <td>${user.email}</td>
               <td>${adminBadge}</td>
               <td>${fecha}</td>
               <td>
                   <!-- Usamos clases en lugar de IDs para que no se pisen -->
                   <button class="btn btn-primary btn-sm btn-editar" data-id="${user.id}">Editar</button>
                   <button class="btn btn-danger btn-sm btn-eliminar" data-id="${user.id}">Eliminar</button>
               </td>
           `;

           // Evento para Editar
           const btnEditarLocal = tr.querySelector(".btn-editar");
           btnEditarLocal.addEventListener("click", () => {
               abrirModalEditar(user);
           });

           // Evento para Eliminar
           const btnEliminarLocal = tr.querySelector(".btn-eliminar");
           btnEliminarLocal.addEventListener("click", async () => {
               if (
                   confirm("¿Seguro que deseas eliminar a " + user.name + "?")
               ) {
                   await eliminarUsuario(user.id);
               }
           });

           tbody.appendChild(tr);
       });

       if (data.length === 0) {
           tbody.innerHTML =
               '<tr><td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td></tr>';
       }
   } catch (error) {
       console.error("Error obteniendo usuarios:", error);
       const tbody = document.getElementById("tablaUsuariosBody");
       tbody.innerHTML =
           '<tr><td colspan="6" class="text-center text-danger">Error al cargar los usuarios.</td></tr>';
   } */
