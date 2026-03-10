<?php

namespace App\Http\Controllers;

use App\Events\TareaCreate;
use App\Models\Tarea;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            if(isset($request->prioridad)){
                return response()->json(Tarea::where("prioridad",$request->prioridad));
            }
            $tareas = Tarea::with('proyecto')->get();
            return response()->json($tareas);
        }catch(Exception $e){
            return response()->json([
                "error"=>"no se han podido cargar las tareas",
                "fail"=>$e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tarea = new Tarea();
        $tarea->descripcion = $request->descripcion;
        $tarea->tiempo_inicio = $request->tiempo_inicio;
        $tarea->tiempo_fin = $request->tiempo_fin;
        $tarea->proyecto_id = $request->proyecto_id;
        $tarea->prioridad = $request->prioridad;
        $tarea->id_user = Auth::user()->id;

        $tarea->save();

        event(new TareaCreate($tarea));
        return response()->json([
            "success"=>"la tarea se ha creado correctamente"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tarea = Tarea::find($id);
        if(!$tarea){
            return response()->json(["message"=> "tarea no encontrada"],404);
        }
        return response()->json($tarea);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tarea = Tarea::find($id);
        if(!$tarea){
            return response()->json(["message"=> "tarea no encontrada"],404);
        }

        $tarea->descripcion = $request->descripcion;
        $tarea->tiempo_inicio = $request->tiempo_inicio;
        $tarea->tiempo_fin = $request->tiempo_fin;
        $tarea->proyecto_id = $request->proyecto_id;

        $tarea->save();
        return response()->json(["message" => "Tarea actualizada exitosamente", "tarea" => $tarea]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tarea = Tarea::find($id);
        if(!$tarea){
            return response()->json(["message"=> "tarea no encontrada"],404);
        }

        $tarea->delete();

        return response()->json(["message"=> "tarea borrada exitosamente"],200);
    }
}
