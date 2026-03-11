<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generarPdf(Request $request)
    {
        try {
            if ($request->prioridad == 0) {
                $tareas = Tarea::where('id_user', $request->user)
                    ->where('proyecto_id', $request->proyecto)
                    ->whereDate('tiempo_inicio', '>=', $request->fecha_inicio)
                    ->whereDate('tiempo_fin', '<=', $request->fecha_fin)
                    ->orderBy('prioridad', 'ASC')
                    ->get();
            } else {
                $tareas = Tarea::where('id_user', $request->user)
                    ->where('proyecto_id', $request->proyecto)
                    ->whereDate('tiempo_inicio', '>=', $request->fecha_inicio)
                    ->whereDate('tiempo_fin', '<=', $request->fecha_fin)
                    ->where('prioridad', $request->prioridad)
                    ->get();
            }

            $proyecto = Proyecto::findOrFail($request->proyecto);
            $usuario = User::findOrFail($request->user);

            // Usar las fechas enviadas desde el frontend para la cabecera
            $fechaDesde = $request->fecha_inicio
                ? Carbon::parse($request->fecha_inicio)->format('d/m/Y')
                : '-';

            $fechaHasta = $request->fecha_fin
                ? Carbon::parse($request->fecha_fin)->format('d/m/Y')
                : '-';

            $pdf = Pdf::loadView('pdf.informe-tareas', [
                'tareas' => $tareas,
                'proyecto' => $proyecto,
                'usuario' => $usuario,
                'fechaDesde' => $fechaDesde,
                'fechaHasta' => $fechaHasta,
            ]);

            return $pdf->download("Tareas");
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
