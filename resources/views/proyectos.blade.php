@extends('adminlte::page')

@section('title', 'Proyectos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Proyectos</h1>
        <!-- Botón para abrir el Modal de Creación (Solo Administradores) -->
        @if (auth()->check() && auth()->user()->administrador)
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearProyecto">
                    <i class="fas fa-plus mr-1"></i> Crear Proyecto
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalGenerarPdf"
                    id="generarPdf">
                    <i class="fas fa-plus mr-1"></i> Generar PDF
                </button>
            </div>
        @endif
    </div>
@stop

@section('content')
    <div class="row">

        <!-- Columna de la Lista de Proyectos u otra información -->
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Proyectos</h3>
                </div>
                <div class="card-body">
                    <div id="listaProyectosArrastrables"></div>
                    <div id="listaProyectos" class="mt-2"></div>
                </div>
            </div>
        </div>

        <!-- Columna reservada para el Calendario -->
        <div class="col-md-8">
            <div class="card card-outline card-success">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0"><i class="far fa-calendar-alt mr-1"></i> Calendario de Proyectos</h3>
                    <div class="ml-auto" style="min-width:200px">
                        <select id="filtroUsuarioCalendario" class="form-control form-control-sm">
                            <option value="">Todos los usuarios</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <!-- ESPACIO RESERVADO PARA EL CALENDARIO -->
                    <!-- Aquí inicializarás FullCalendar u otra librería -->
                    <div id="calendarioProyectos"
                        style="min-height: 400px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; background: #f9f9f9;">
                        <span class="text-muted">Espacio reservado para el Calendario de Proyectos</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal de Creación de Proyecto (AJAX) -->
    <div class="modal fade" id="modalCrearProyecto" tabindex="-1" role="dialog" aria-labelledby="modalCrearProyectoLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="modalCrearProyectoLabel">Crear Nuevo Proyecto</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Formulario AJAX solo con nombre -->
                <div class="modal-body">
                    <form id="formCrearProyecto">
                        @csrf
                        <div class="form-group">
                            <label for="p_nombre">Nombre del Proyecto</label>
                            <input type="text" class="form-control" id="p_nombre" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <!-- El JS de este botón es el que debe hacer el fetch/ajax -->
                    <button type="button" class="btn btn-primary" id="btnGuardarProyecto">Guardar Proyecto</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Añadir Tarea (Drap & Drop) -->
    <div class="modal fade" id="modalCrearTarea" tabindex="-1" role="dialog" aria-labelledby="modalCrearTareaLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalCrearTareaLabel">Nueva Tarea en Proyecto</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCrearTarea">
                        @csrf
                        <input type="hidden" id="t_proyecto_id">

                        <div class="form-group">
                            <label>Proyecto Seleccionado</label>
                            <input type="text" class="form-control" id="t_proyecto_nombre" disabled>
                        </div>

                        <div class="form-group">
                            <label for="t_descripcion">Descripción</label>
                            <textarea class="form-control" id="t_descripcion" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-6 form-group">
                                <label for="t_inicio">Inicio</label>
                                <input type="datetime-local" class="form-control" id="t_inicio" required>
                            </div>
                            <div class="col-6 form-group">
                                <label for="t_fin">Fin</label>
                                <input type="datetime-local" class="form-control" id="t_fin" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarTarea">Guardar Tarea</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ver Tareas del Proyecto (Click en evento del calendario) -->
    <div class="modal fade" id="modalVerTareas" tabindex="-1" role="dialog" aria-labelledby="modalVerTareasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalVerTareasLabel">
                        <i class="fas fa-tasks mr-1"></i> Tareas del Proyecto: <span
                            id="modal_proyecto_nombre_titulo"></span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="listaTareasModal">
                        <p class="text-muted text-center"><i class="fas fa-spinner fa-spin"></i> Cargando tareas...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Generar PDF -->
    <div class="modal fade" id="modalGenerarPdf" tabindex="-1" role="dialog" aria-labelledby="modalGenerarPdfLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalGenerarPdfLabel">
                        <i class="fas fa-file-pdf mr-1"></i> Generar Informe PDF
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="pdf_usuario" class="form-label fw-bold">Usuario</label>
                            <select id="pdf_usuario" class="form-control">
                                <option value="">Seleccione un usuario...</option>
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="pdf_proyecto" class="form-label fw-bold">Proyecto</label>
                            <select id="pdf_proyecto" class="form-control">
                                <option value="">Seleccione un proyecto...</option>
                                @foreach ($proyectos as $proyecto)
                                    <option value="{{ $proyecto->id }}">{{ $proyecto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3"> <label for="pdf_fecha_inicio"
                                class="form-label fw-bold">Fecha Inicio</label>
                            <input type="date" id="pdf_fecha_inicio" name="fecha_inicio" class="form-control">
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="pdf_fecha_fin" class="form-label fw-bold">Fecha Fin</label>
                            <input type="date" id="pdf_fecha_fin" name="fecha_fin" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnDescargarPdf">
                        <i class="fas fa-download mr-1"></i> Generar PDF
                    </button>
                </div>

            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- CSS de FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <style>
        .proyecto-draggable {
            cursor: move;
            z-index: 1050;
        }
    </style>
@stop

@section('js')
    {{-- Cuando crees tu archivo JS para los proyectos, mételo aquí por vite --}}
    @vite(['resources/js/proyectos.js'])
@stop
