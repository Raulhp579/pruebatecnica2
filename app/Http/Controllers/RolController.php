<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Rol_Permiso;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $model = Rol::query();

        return DataTables::eloquent($model)
            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('nombre', function ($row) {
                return $row->nombre;
            })
            ->addColumn('descripcion', function ($row) {
                return $row->descripcion;
            })
            ->make(true);
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
        $rol = Rol::with('permisos')->where("id",$id)->first();

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
        $id_rol = $request->id_rol;
        $id_permisos = $request->id_permisos; // Array of IDs

        if (!is_array($id_permisos)) {
            $id_permisos = [];
        }

        // Delete existing for this role to sync
        Rol_Permiso::where('id_rol', $id_rol)->delete();

        foreach ($id_permisos as $id_permiso) {
            $rol_permiso = new Rol_Permiso();
            $rol_permiso->id_rol = $id_rol;
            $rol_permiso->id_permiso = $id_permiso;
            $rol_permiso->save();
        }

        return response()->json([
            "success"=>"permisos asociados al rol correctamente"
        ]);
    }
}
