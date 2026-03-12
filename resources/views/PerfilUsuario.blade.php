@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1><i class="fas fa-user-circle text-primary mr-2"></i> Mi Perfil</h1>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card card-primary card-outline shadow">

                <div class="card-body box-profile mt-2">
                    <div class="text-center mb-4">
                        <img class="profile-user-img img-fluid img-circle shadow-sm"
                             src=""
                             alt="Avatar del usuario"
                             id="avatarNombre">
                        <h3 class="profile-username text-center mt-2" id="nombreUsuario">{{ auth()->user()->name ?? 'Tu Nombre' }}</h3>
                        <p class="text-muted text-center" id="tituloCorreo">{{ auth()->user()->email ?? 'tu@email.com' }}</p>
                    </div>

                    <hr class="mb-4">

                    <h5 class="text-primary mb-3"><i class="fas fa-id-card mr-2"></i> Datos Personales</h5>

                    <div class="form-group">
                        <label for="perfil_name">Nombre</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="perfil_name" value="{{ auth()->user()->name ?? '' }}" placeholder="Tu nombre completo" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="perfil_email">Correo Electrónico</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" id="perfil_email" value="{{ auth()->user()->email ?? '' }}" placeholder="tu@email.com" required>
                        </div>
                    </div>

                    <hr class="mt-4 mb-4">
                    <h5 class="text-danger mb-3"><i class="fas fa-shield-alt mr-2"></i> Seguridad</h5>

                    <div class="form-group">
                        <label for="perfil_password_actual">Contraseña Actual</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                            </div>
                            <input type="password" class="form-control" id="perfil_password_actual" placeholder="Ingresa tu contraseña actual">
                        </div>
                        <small class="form-text text-muted">Obligatorio solo si deseas establecer una nueva contraseña.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perfil_password">Nueva Contraseña</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="perfil_password" placeholder="••••••••">
                                </div>
                                <small class="form-text text-muted">Déjalo en blanco para mantener la actual.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perfil_password_confirmation">Confirmar Nueva Contraseña</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="perfil_password_confirmation" placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <button type="button" class="btn btn-primary px-4 shadow-sm" id="btnGuardarPerfil">
                        <i class="fas fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .profile-user-img {
            border: 3px solid #adb5bd;
            padding: 3px;
        }
    </style>
@stop

@section('js')
    @vite(['resources/js/perfil.js', 'resources/js/app.js'])
@stop
