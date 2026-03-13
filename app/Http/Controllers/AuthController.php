<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User_Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request){

        $user = User::where("email", $request->email)->first();

        if(!$user){
            return response()->json([
                "error"=>"el email es incorrecto"
            ]);
        }

        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                "error"=>"la contraseña es incorrecta"
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json($token);
    }


    public function register(Request $request){

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        $user->save();


        User_Rol::create([
            'id_user'=>$user->id,
            'id_rol'=>2
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json($token);

    }

    public function logout(){
        $user = Auth::user();


        $user->tokens()->delete();

        return response()->json([
            "success"=>"sesión cerrada exitosamente"
        ]);

    }
}
