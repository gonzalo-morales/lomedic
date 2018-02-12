<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class api
{
    public function handle($request, Closure $next){

//        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
//        {
//            if(Auth::attempt(["usuario"=>$_SERVER['PHP_AUTH_USER'],"password"=>$_SERVER['PHP_AUTH_PW']])){//Si encuentra el usuario y la contraseña
                return $next($request);//Continúa
//            }
//        }
        return response()->json(["status"=>"No Autorizado","data"=>"Error (401)"], 401);//No autorizado
    }
}