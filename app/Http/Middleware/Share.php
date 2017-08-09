<?php

namespace App\Http\Middleware;

use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Soporte\Categorias;
use App\Http\Models\Soporte\Acciones;
use App\Http\Models\Soporte\Prioridades;
use App\Http\Models\Soporte\Subcategorias;
use Closure;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
		# Compartimos modulos de usuario para generar menu
		View::share('menu', Auth::user()->modulos_anidados());
		View::share('employees_tickets', Empleados::all());
		View::share('branches_tickets', Sucursales::all());
		View::share('categories_tickets', Categorias::all());
		View::share('subcategories_tickets', Categorias::with('Subcategorias')->get());
		View::share('priorities_tickets', Prioridades::all());

		return $next($request);
	}
}