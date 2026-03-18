@extends('adminlte::page')

@section('title', 'Tablero de Tareas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-columns mr-2"></i> Tablero de Tareas</h1>
        <div>
            <button class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-1"></i> Nueva Tarea
            </button>
        </div>
    </div>
@stop

@section("css")
    @vite(['resources/css/app.css'])
@stop

@section('content')
    <div class="row">

        <!-- ===== COLUMNA: POR APROBAR ===== -->
        <div class="col-md">
            <div class="card card-outline card-secondary">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-hourglass-start text-secondary mr-1"></i> Por Aprobar
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-secondary badge-pill">1</span>
                    </div>
                </div>
                <div id="col-por-aprobar" class="card-body p-2 kanban-column" style="min-height: 500px; background-color: #f4f6f9;">
                    
                        

                </div>
            </div>
        </div>

        <!-- ===== COLUMNA: APROBADO ===== -->
        <div class="col-md" >
            <div class="card card-outline card-info">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle text-info mr-1"></i> Aprobado
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info badge-pill">3</span>
                    </div>
                </div>
                <div id="col-aprobado" class="card-body p-2 kanban-column" style="min-height: 500px; background-color: #f4f6f9;">
                    
                   

                </div>
            </div>
        </div>

        <!-- ===== COLUMNA: DESARROLLO ===== -->
        <div class="col-md" >
            <div class="card card-outline card-warning">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-code text-warning mr-1"></i> Desarrollo
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning text-dark badge-pill">2</span>
                    </div>
                </div>
                <div id="col-desarrollo" class="card-body p-2 kanban-column" style="min-height: 500px; background-color: #f4f6f9;">
                    
                    

                </div>
            </div>
        </div>

        <!-- ===== COLUMNA: TESTING ===== -->
        <div class="col-md" >
            <div class="card card-outline card-primary">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-vial text-primary mr-1"></i> Testing
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary badge-pill">1</span>
                    </div>
                </div>
                <div id="col-testing" class="card-body p-2 kanban-column" style="min-height: 500px; background-color: #f4f6f9;">
                    
                    

                </div>
            </div>
        </div>

        <!-- ===== COLUMNA: FINALIZADO ===== -->
        <div class="col-md" >
            <div class="card card-outline card-success">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-check-all text-success mr-1"></i> Finalizado
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-success badge-pill">2</span>
                    </div>
                </div>
                <div id="col-finalizado" class="card-body p-2 kanban-column" style="min-height: 500px; background-color: #f4f6f9;">
                    
                    

                </div>
            </div>
        </div>

    </div>
@stop

@section('css')
    <style>
        /* Estilos para el Tablero Kanban */
        .kanban-column {
            border-radius: 4px;
            overflow-y: auto;
            max-height: calc(100vh - 200px);
        }
        
        .kanban-item {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .kanban-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.12) !important;
        }

        /* Opcional: Estilo de "arrastrable" si luego se añade JS */
        .proyecto-draggable {
            cursor: move;
        }
    </style>
@stop

@section("js")
    @vite(['resources/js/app.js', 'resources/js/proyectoTareas.js'])
@stop
