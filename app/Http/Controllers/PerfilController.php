<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class PerfilController extends Controller
{
    public function cambiarDatos(Request $request){


        $id = Auth::user()->id;

        $user = User::where("id", $id)->first();

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return response()->json([
            "success"=> "se han cambiado los datos del usuario con exito"
        ]);
    }

    public function cambiarPassword(Request $request){
        $id = Auth::user()->id;

        $user = User::where("id", $id)->first();

        if(!Hash::check($request->actual, $user->password)){
            return response()->json([
                "error"=>"La contraseña actual no coincide"
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            "success"=>"contraseña cambiada correctamente"
        ]);
    }
}
