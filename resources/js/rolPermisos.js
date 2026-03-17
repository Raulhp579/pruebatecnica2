import $ from "jquery";
import DataTable from "datatables.net-dt";

document.addEventListener("DOMContentLoaded",async ()=>{
    const data = await cargarRoles()

    cargarTabla(data)
})

const editarRol =async (id, datos) => {
    const response = await fetch(`/api/rol/${id}`,{
        method: 'PUT',
        headers:{
            'Content-Type':'application/json',
            'Authorization':`Bearer ${localStorage.getItem('AuthToken')}`
        },
        body:JSON.stringify(datos)
    })

    const data = await response.json()

    console.log(data)
}

const cargarRoles = async () => {
    const response = await fetch("/api/rol",{
        method:'GET',
        headers:{
            'Authorization':`Bearer ${localStorage.getItem('AuthToken')}`
        }
    })

    const data = await response.json()

    console.log(data)

    return data

}

const cargarTabla = (data) => {
    var lang = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
            "copy": "Copiar",
            "colvis": "Visibilidad"
        }
    }
    $(document).ready(function () {
        //Enlazando tabla con datos AJAX
        var table = $('#tablaRoles').DataTable({
            language: lang,
            data: data,
            dom: '<"d-flex justify-content-between align-items-center flex-wrap px-2 pt-2"lf>t<"d-flex justify-content-between align-items-center flex-wrap p-2"ip>',
            initComplete: function () {
                $('.dataTables_length select').addClass('custom-select custom-select-sm d-inline-block mx-1').css('width', 'auto');
                $('.dataTables_filter input').addClass('form-control form-control-sm d-inline-block ml-1').css('width', 'auto');
            },
            columns: [{
                data: 'id'
            }, {
                data: 'nombre'
            }, {
                data: 'descripcion'
            },
            {
                data: null,
                 render: function (data, type, row, meta) {
                        return (
                            '<a class="btn btn-sm btn-success btnEdit" style="margin-right: 5px; cursor: pointer;"><i class="fa fa-edit"></i></a>' +
                            '<a class="btn btn-sm btn-danger btnDelete" style="cursor: pointer;"><i class="fa fa-trash"></i></a>'
                        );
                    },

            }
            ],
        });

        //En lazando botones de la tabla
        $(document).on('click', '.btnEdit', function () {
            var data = table.row($(this).parents('tr')).data();
            abrirModalEditar(data['id'])
        });

        $(document).on('click', '.btnDelete', function () {
            var data = table.row($(this).parents('tr')).data();
            alert(data['salary']);
        });
    });


}


const abrirModalEditar = (id) => {
    window.$("#modalEditarRol").modal("show");
    const nombreRol = document.querySelector("#edit_rol_nombre")
    const descripcionRol = document.querySelector("#edit_rol_descripcion")
    const botonGuardarCambiosRol = document
}
