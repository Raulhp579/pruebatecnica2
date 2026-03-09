document.addEventListener('DOMContentLoaded', cargarUsuarios);

async function cargarUsuarios() {
    try {
        const response = await fetch("/api/user");
        const data = await response.json();
        
        const tbody = document.getElementById('tablaUsuariosBody');
        tbody.innerHTML = ''; 
        
        // Iteramos los usuarios devueltos
        data.forEach(user => {
            const tr = document.createElement('tr');
            
            const adminBadge = user.administrador 
                ? '<span class="badge bg-success">Sí</span>' 
                : '<span class="badge bg-danger">No</span>';
                
            const fecha = user.created_at ? new Date(user.created_at).toLocaleDateString('es-ES') : 'N/A';

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
            const btnEditarLocal = tr.querySelector('.btn-editar');
            btnEditarLocal.addEventListener('click', () => {
                abrirModalEditar(user);
            });

            // Evento para Eliminar
            const btnEliminarLocal = tr.querySelector('.btn-eliminar');
            btnEliminarLocal.addEventListener('click', async () => {
                if(confirm('¿Seguro que deseas eliminar a ' + user.name + '?')) {
                    await eliminarUsuario(user.id);
                }
            });

            tbody.appendChild(tr);
        });
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td></tr>';
        }
        
    } catch (error) {
        console.error("Error obteniendo usuarios:", error);
        const tbody = document.getElementById('tablaUsuariosBody');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error al cargar los usuarios.</td></tr>';
    }
}

// Llenamos el modal con los datos del usuario correspondiente y lo mostramos
function abrirModalEditar(user) {
    document.getElementById('edit_id').value = user.id;
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_password').value = ''; // La contraseña siempre en blanco por seguridad
    document.getElementById('edit_administrador').checked = user.administrador;
    
    // Usamos jQuery (que viene incluido en AdminLTE) para mostrar el modal de Bootstrap
    $('#modalEditarUsuario').modal('show');
}

// Evento para el botón de Guardar Cambios dentro del modal
document.getElementById('btnGuardarEdicion').addEventListener('click', async () => {
    const id = document.getElementById('edit_id').value;
    
    const usuarioActualizado = {
        nombre: document.getElementById('edit_name').value,
        correo: document.getElementById('edit_email').value,
        esAdmin: document.getElementById('edit_administrador').checked
    };
    
    // Solo enviamos la contraseña si el usuario ha escrito algo nuevo
    const nuevaPassword = document.getElementById('edit_password').value;
    if (nuevaPassword.trim() !== '') {
        usuarioActualizado.contrasena = nuevaPassword;
    }

    try {
        const response = await fetch(`/api/user/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(usuarioActualizado)
        });
        
        const data = await response.json();
        console.log("Respuesta al editar:", data);
        
        // Escondemos el modal y recargamos la tabla
        $('#modalEditarUsuario').modal('hide');
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
                "Content-Type": "application/json"
            }
        });
        
        const data = await response.json();
        console.log("Respuesta al eliminar:", data);
        
        cargarUsuarios();
        
    } catch (error) {
        console.error("Error al eliminar el usuario:", error);
    }
}

