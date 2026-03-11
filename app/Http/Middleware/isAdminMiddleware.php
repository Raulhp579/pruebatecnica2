<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()){
            return response()->json([
                "error"=>"no estas autenticado"
            ]);
        }

        $user = Auth::user();

        if(Auth::user()->rol != 1){
            return response()->json([
                "error"=>"el usuario no es administrador"
            ]);
        }


        return $next($request);
    }
}
