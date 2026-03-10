@extends('adminlte::page')

@section('title', 'Prueba Técnica')

@section('content_header')
    <h1>Encabezado - Prueba Técnica</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Sección Principal</h3>
        </div>
        <div class="card-body">
            <p>¡Bienvenido a la prueba técnica usando AdminLTE en Laravel!</p>
            <p>Aquí puedes colocar el contenido principal de tu aplicación.</p>
        </div>
        <div class="card-footer">
            <p class="mb-0 text-muted">Este es el pie de página de la tarjeta de contenido.</p>
        </div>
    </div>
@stop

@section('footer')
    <div class="float-right d-none d-sm-block ">
        <b>Versión</b> 1.0.0
    </div>
    <div class="d-flex justify-content-center">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">Prueba Técnica SIMJ</a></strong>
    </div>
@stop

@section('css')
    {{-- Agrega tu CSS personalizado aquí --}}
@stop

@section('js')
    <script>
        console.log('Vista admin cargada correctamente');
    </script>
@stop
