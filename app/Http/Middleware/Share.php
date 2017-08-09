<?php

namespace App\Http\Middleware;

use App\Http\Models\Administracion\Empresas;
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
		# Compartimos empresa
		View::share('empresa', Empresas::where('conexion', request()->company)->first());

		# Compartimos otras empresas
		View::share('empresas', Empresas::where('conexion', '!=', request()->company)->get());

		# Compartimos modulos de usuario para generar menu
		View::share('menu', Auth::user()->modulos_anidados());

		return $next($request);
	}
}