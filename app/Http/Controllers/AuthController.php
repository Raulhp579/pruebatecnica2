<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
}
