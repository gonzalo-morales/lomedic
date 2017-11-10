<?php
namespace App\Http\Middleware;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Soporte\Categorias;
use App\Http\Models\Soporte\Prioridades;
use App\Http\Models\Soporte\Solicitudes;
use App\Http\Models\Soporte\Subcategorias;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
class Share
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        # Compartimos empresas activas
        $empresas = Empresas::where('activo',1)->get();
        View::share('empresa', $empresas->where('conexion', '=', request()->company)->first());
        View::share('empresas', $empresas->where('conexion', '!=', request()->company));

        # Compartimos modulos de usuario para generar menu
        View::share('menu', Auth::user()->modulos_anidados($empresas->where('conexion', '=', request()->company)->first()));
        
        # Compartimos ultimos tickets
        View::share('ultimos_tickets',Solicitudes::where('fk_id_empleado_solicitud',Auth::id())->where('fecha_hora_resolucion',null)->take(5)->get());
        return $next($request);
    }
}