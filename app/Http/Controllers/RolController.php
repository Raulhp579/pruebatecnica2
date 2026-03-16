<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Rol_Permiso;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Rol::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rol = new Rol();
        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;

        $rol->save();

        return response()->json([
            "success"=>"rol creado correctamente"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rol = Rol::where("id",$id)->first();

        if(!$rol){
            return response()->json([
                "error"=>"no se ha encontrado el rol"
            ]);
        }

        return response()->json($rol);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rol = Rol::where("id",$id)->first();

        if(!$rol){
            return response()->json([
                "error"=>"no se ha encontrado el rol"
            ]);
        }

        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;

        $rol->save();

        return response()->json([
            "success"=>"el rol se ha actualizado correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Rol::where("id",$id)->first();

        if(!$rol){
            return response()->json([
                "error"=>"no se ha encontrado el rol"
            ]);
        }

        $rol->delete();

        return response()->json([
            "success"=>"rol borrado correctamente"
        ]);
    }

    public function asociarPermisoRol(Request $request){
        $rol_permiso = new Rol_Permiso();
        $rol_permiso->id_rol = $request->id_rol;
        $rol_permiso->id_permiso = $request->id_permiso;

        $rol_permiso->save();

        return response()->json([
            "success"=>"permiso asociado al rol correctamente"
        ]);

        
    }
}
