<?php
namespace App\Http\Middleware;

use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Soporte\Solicitudes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Models\Administracion\TiposDocumentos;
use Closure;
use File;

class Share
{
    public function handle($request, Closure $next)
    {
        $menuempresas = Empresas::where('activo',1)->get();
        View::share('menuempresa', $menuempresas->where('conexion', '=', request()->company)->first());
        View::share('menuempresas', $menuempresas->where('conexion','!=', null)->where('conexion', '!=', request()->company));
        
        # Compartimos modulos de usuario para generar menu
        View::share('menu', Auth::user()->modulos_anidados($menuempresas->where('conexion', '=', request()->company)->first()));
        
        # Compartimos ultimos tickets
        View::share('ultimos_tickets',Solicitudes::where('fk_id_empleado_solicitud',Auth::id())->where('fecha_hora_resolucion',null)->take(5)->get());
        
        $tipos_documentos = [];
        
        foreach(File::allFiles(app_path().'/Http/Models') as $route) {
            if(preg_match("/^.*.php$/", $route->getPathname())){
                $smodel = substr(str_replace([base_path().'\a','/'],['A','\\'],$route->getPathname()),0,-4);
                
                $tipo = TiposDocumentos::where('tabla',(new $smodel)->getTable())->first();
                
                
                
                if(!empty($tipo)) {
                    $tipos_documentos[$tipo->id_tipo_documento] = $smodel;
                }
                
                
            }
        }
        View::share('map_tipos_documentos', $tipos_documentos);
        
        return $next($request);
    }
}