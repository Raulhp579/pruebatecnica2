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
        }else{
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

    cargarTabla(data.data);
}

// Llenamos el modal con los datos del usuario correspondiente y lo mostramos
async function abrirModalEditar(user) {
    document.getElementById("edit_id").value = user.id;
    document.getElementById("edit_name").value = user.name;
    document.getElementById("edit_email").value = user.email;
    document.getElementById("edit_password").value = ""; // La contraseña siempre en blanco por seguridad
    
    // Si viene como string de la tabla de listado
    const esAdmin = typeof user.administrador === "string" 
        ? user.administrador === "Es administrador" 
        : user.administrador === 1;
        
    document.getElementById("edit_administrador").checked = esAdmin;

    const container = document.getElementById("listaRolesUsuario");
    container.innerHTML = '<p class="text-muted mb-0"><i class="fas fa-spinner fa-spin"></i> Cargando...</p>';

    try {
        const response = await fetch(`/api/getUserRol/${user.id}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem("AuthToken")}`
            }
        });
        const roles = await response.json();
        container.innerHTML = "";

        if (roles.length === 0) {
            container.innerHTML = '<p class="text-muted mb-0">Sin roles asignados.</p>';
        } else {
            roles.forEach(r => {
                const badge = document.createElement("span");
                badge.className = "badge bg-info d-flex align-items-center px-2 py-1 mr-1 mb-1";
                badge.style.gap = "5px";
                badge.style.fontSize = "12px";
                const rolNombre = r.rol ? r.rol.nombre : `Rol #${r.id_rol}`;
                badge.innerHTML = `${rolNombre} <i class="fas fa-trash-alt text-danger btn-eliminar-rol ml-1" style="cursor: pointer;" data-idrol="${r.id_rol}"></i>`;
                container.appendChild(badge);
            });

            container.querySelectorAll(".btn-eliminar-rol").forEach(btn => {
                btn.addEventListener("click", async (e) => {
                    const idRol = e.target.getAttribute("data-idrol");
                    if (confirm(`¿Seguro que deseas quitar el rol?`)) {
                        await desasociarRol(user.id, idRol);
                        abrirModalEditar(user); // Recargar la lista
                    }
                });
            });
        }
    } catch (error) {
        container.innerHTML = '<span class="text-danger">Error al cargar roles.</span>';
    }

    // Usamos jQuery para mostrar el modal
    window.$("#modalEditarUsuario").modal("show");
}

async function desasociarRol(idUsuario, idRol) {
    const datos = {
        id_user: idUsuario,
        id_rol: idRol
    };

    const response = await fetch("/api/desasociarRolUsuario", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem("AuthToken")}`
        },
        body: JSON.stringify(datos)
    });

    const data = await response.json();
    console.log("Respuesta desasociar:", data);
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

btnAñadirUsuario.addEventListener("click", async (e) => {

    e.preventDefault()
    const rol = document.querySelector("#add_administrador").checked

    const usuarioAñadido = {
        nombre: document.getElementById("add_name").value,
        correo: document.getElementById("add_email").value,
        contrasena: document.getElementById("add_password").value,
        esAdmin: rol?1:2,
    };



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
        cargarUsuarios();
    } catch (error) {
        console.error("Error al añadir el usuario:", error);
    }
});


const btnGuardarRol = document.querySelector("#btnGuardarRol")

btnGuardarRol.addEventListener("click", async () => {
    const idUsuario = document.getElementById("edit_id").value;
    const idRol = document.querySelector("#edit_rol").value

    const datos = {
        id_user : idUsuario,
        id_rol : idRol
    }

    console.log(datos)

    const response =await fetch("/api/asociarRolUsuario",{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'Authorization':`Bearer ${localStorage.getItem("AuthToken")}`
        },
        body:JSON.stringify(datos)
    })

    const data = await response.json();
    console.log(data);

    // Recargar la lista de roles en el modal
    abrirModalEditar({
        id: idUsuario,
        administrador: document.getElementById("edit_administrador").checked ? "Es administrador" : "No es administrador",
        name: document.getElementById("edit_name").value,
        email: document.getElementById("edit_email").value
    });
})
