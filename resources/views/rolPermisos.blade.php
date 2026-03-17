@extends('adminlte::page')

@section('title', 'Roles y Permisos')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css">
    @vite(['resources/css/app.css'])
@stop

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Gestión de Roles y Permisos</h1>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Tarjeta de Roles -->
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Roles</h3>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAñadirRol">
                            <i class="fas fa-plus"></i> Añadir Rol
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap table-striped" id="tablaRoles">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaRolesBody">
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin text-primary"></i> Cargando roles...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>


    </div>

    <!-- Modal Añadir Rol -->
    <div class="modal fade" id="modalAñadirRol" tabindex="-1" role="dialog" aria-labelledby="modalAñadirRolLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalAñadirRolLabel">Añadir Nuevo Rol</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAñadirRol">
                        <div class="form-group">
                            <label for="add_rol_nombre">Nombre del Rol</label>
                            <input type="text" class="form-control" id="add_rol_nombre"
                                placeholder="Ej: Gestor, Supervisor" required>
                        </div>
                        <div class="form-group">
                            <label for="add_rol_descripcion">Descripción</label>
                            <textarea class="form-control" id="add_rol_descripcion" rows="3"
                                placeholder="Descripción de las funciones del rol"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarRol">Guardar Rol</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Rol -->
    <div class="modal fade" id="modalEditarRol" tabindex="-1" role="dialog" aria-labelledby="modalEditarRolLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modalEditarRolLabel">Editar Rol</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditarRol">
                        <input type="hidden" id="edit_rol_id">
                        <div class="form-group">
                            <label for="edit_rol_nombre">Nombre del Rol</label>
                            <input type="text" class="form-control" id="edit_rol_nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_rol_descripcion">Descripción</label>
                            <textarea class="form-control" id="edit_rol_descripcion" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnActualizarRol">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Gestionar Permisos (Asignar a Rol) -->
    <div class="modal fade" id="modalPermisos" tabindex="-1" role="dialog" aria-labelledby="modalPermisosLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="modalPermisosLabel">Gestionar Permisos del Rol: <span
                            id="nombreRolAsignar" class="font-weight-bold"></span></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formGestionarPermisos">
                        <input type="hidden" id="asignar_rol_id">
                        <div class="form-group">
                            <label>Selecciona los permisos para este rol:</label>
                            <div id="listaPermisosCheckboxes" class="p-2 border rounded"
                                style="max-height: 250px; overflow-y: auto;">
                                <!-- Se cargarán dinámicamente o por Blade -->
                                @isset($permisos)
                                    @foreach ($permisos as $permiso)
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input class="custom-control-input checkbox-permiso" type="checkbox"
                                                id="permiso_{{ $permiso->id }}" value="{{ $permiso->id }}">
                                            <label for="permiso_{{ $permiso->id }}" class="custom-control-label">
                                                @if ($permiso->tipo_permiso == 0)
                                                    Ver todas las tareas
                                                @endif
                                                @if ($permiso->tipo_permiso == 1)
                                                    Crear Tareas
                                                @endif
                                                @if ($permiso->tipo_permiso == 2)
                                                    Editar Tareas
                                                @endif
                                                @if ($permiso->tipo_permiso == 3)
                                                    Borrar Tareas
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center"><i class="fas fa-spinner fa-spin"></i> Cargando
                                        permisos...</p>
                                @endisset
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-info" id="btnGuardarPermisosRol">Guardar Permisos</button>
                </div>
            </div>
        </div>
    </div>

@stop


@section('js')
    @vite(['resources/js/rolPermisos.js', 'resources/js/app.js'])
@stop
