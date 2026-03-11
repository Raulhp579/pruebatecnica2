<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            /* return response()->json(User::all()); */

            $model = User::query();


            return DataTables::eloquent($model)
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('administrador', function ($row) {
                    if ($row->rol) {
                        return $row->rol->id_rol; 
                    }
                    return "Sin rol asignado";
                })
                ->make(true); 

        } catch (Exception $e) {
            return response()->json([
                'error' => 'no se han podido mostrar los usuarios',
                'fail' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User;

        $user->name = $request->nombre;
        $user->email = $request->correo;
        $user->password = Hash::make($request->contrasena);
        $user->administrador = $request->esAdmin;

        $user->save();

        return response()->json([
            'message' => 'usuario creado correctamente',
            'user' => $user,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'error' => 'usuario no encontrado',
            ]);

        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'error' => 'usuario no encontrado',
            ]);
        }

        $user->name = $request->nombre;
        $user->email = $request->correo;
        $user->password = Hash::make($request->contrasena);
        $user->administrador = $request->esAdmin;

        $user->save();

        return response()->json([
            'message' => 'usuario actualizado correctamente',
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::where('id', $id)->first();

        if (! $user) {
            return response()->json([
                'error' => 'usuario no encontrado',
            ]);
        }

        $user->delete();

        return response()->json([
            'message' => 'usuario eliminado correctamente',
            'user' => $user,
        ]);
    }

    /*     public function pruebaRol(string $id)
        {
            $user = User::where('id', $id)->first();
            $rol = Rol::where("id",1)->first();

            return response()->json($rol->users);
        } */
}
