<?php
namespace App\Http\Middleware;

use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Soporte\Solicitudes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Closure;

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
        
        return $next($request);
    }
}