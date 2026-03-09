@extends('adminlte::page')

@section('title', 'Ver Usuarios')

@section('content_header')
    <h1>Lista de Usuarios</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Usuarios Registrados</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo Electrónico</th>
                                <th>¿Es Administrador?</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaUsuariosBody">
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin text-primary"></i> Cargando usuarios...
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

    <!-- Modal de Edición de Usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditarUsuario">
                        <input type="hidden" id="edit_id">
                        
                        <div class="form-group">
                            <label for="edit_name">Nombre</label>
                            <input type="text" class="form-control" id="edit_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="edit_email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_password">Contraseña (Dejar en blanco para no cambiarla)</label>
                            <input type="password" class="form-control" id="edit_password" placeholder="••••••••">
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input" id="edit_administrador" value="1">
                                <label class="custom-control-label" for="edit_administrador">¿Es Administrador?</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarEdicion">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Aquí podrías añadir CSS adicional si te hace falta --}}
@stop

@section('js')
    @vite(['resources/js/verUsuarios.js'])
@stop
