<?php

namespace App\Http\Middleware;

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

		return $next($request);
	}
}