<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use Exception;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response()->json(Permiso::all());
        }catch(Exception $e){
            return response()->json([
                "error"=>"error al mostrar los permisos",
                "fail"=>$e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $permiso = new Permiso();

            $permiso->tipo_permiso = $request->tipo;

            $permiso->save();

            return response()->json([
                "success"=>"permiso creado correctamente"
            ]);

        }catch(Exception $e){
            return response()->json([
                "error"=>"no se ha podido crear el permiso",
                "fail"=>$e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permiso = Permiso::where("id",$id)->first();

        if(!$permiso){
            return response()->json([
                "error"=>"no se ha podido encontrar el permiso"
            ]);
        }

        return response()->json($permiso);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permiso = Permiso::where("id",$id)->first();

        if(!$permiso){
            return response()->json([
                "error"=>"no se ha podido encontrar el permiso"
            ]);
        }

        $permiso->tipo_permiso = $request->tipo;

        $permiso->save();

        return response()->json([
            "success"=>"permiso actualizado correctamente"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permiso = Permiso::where("id",$id)->first();

        if(!$permiso){
            return response()->json([
                "error"=>"no se ha podido encontrar el permiso"
            ]);
        }

        $permiso->delete();

        return response()->json([
            "success"=>"permiso borrado correctamente"
        ]);
    }
}
