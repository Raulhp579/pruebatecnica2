<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proyectos = Proyecto::all();
        return response()->json($proyectos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $proyecto = new Proyecto();
        
        $proyecto->nombre = $request->nombre;
        
        $proyecto->save();
        return response()->json(["message" => "Proyecto creado exitosamente", "proyecto" => $proyecto], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $proyecto = Proyecto::find($id);
        if(!$proyecto){
            return response()->json(["message"=> "proyecto no encontrado"],404);
        }
        return response()->json($proyecto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $proyecto = Proyecto::find($id);
        if(!$proyecto){
            return response()->json(["message"=> "proyecto no encontrado"],404);
        }
        $proyecto->nombre = $request->nombre;
        
        $proyecto->save();
        return response()->json(["message" => "Proyecto actualizado exitosamente", "proyecto" => $proyecto]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proyecto = Proyecto::find($id);
        if(!$proyecto){
            return response()->json(["message"=> "proyecto no encontrado"],404);
        }

        $proyecto->delete();

        return response()->json(["message"=> "proyecto borrado exitosamente"],200);
    }
}
