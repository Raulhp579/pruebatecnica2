<?php

namespace App\Http\Middleware;

use App\Models\Permiso;
use App\Models\Rol;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class puedeVerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::user()->id;

        $user = User::where('id', $userId)->first();
        $id_rol =$user->rol->id_rol;
        $rol = Rol::where("id", $id_rol)->first();

        foreach($rol->permisos as $permiso){
            $perm = Permiso::where("id",$permiso->id_permiso)->first();

            if($perm->tipo_permiso == 0){
                return $next($request);
            }
        }



        $user_permisos = $user->permisos;

        foreach($user_permisos as $up){
            $permiso = Permiso::where("id", $up->id_permiso)->first();
            if($permiso->tipo_permiso == 0){
                return $next($request);
            }

        }

        return response()->json([
            "error"=>"el usuario no tiene el permiso para poder ver"
        ]);
    }
}
