@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1>Crear Nuevo Usuario</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Datos del Usuario</h3>
                </div>
                
                <form id="formCrearUsuario">
                    @csrf
                    <div class="card-body">
                        
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Ej. Juan Pérez" required>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="ejemplo@correo.com" required>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                        </div>

                        <!-- Admin Checkbox -->
                        <div class="form-group">
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input" id="administrador" name="administrador" value="1">
                                <label class="custom-control-label" for="administrador">¿Es Administrador?</label>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="button" id="btnCrearUsuario" class="btn btn-primary">Crear Usuario</button>
                        <a href="/" class="btn btn-default float-right">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Agrega tu CSS personalizado aquí --}}
@stop

@section('js')
    <script>
        console.log('Formulario de creación cargado correctamente');
    </script>
    @vite(['resources/js/crearUsuario.js'])
@stop
