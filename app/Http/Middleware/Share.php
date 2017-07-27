<?php

namespace App\Http\Middleware;

use Closure;

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

		# Menu
		$modulos = \Auth::user()->modulos();

		// dump( 'modulos usuarios' );
		// dump( $modulos->pluck('nombre')->toArray() );

		// foreach ($modulos as $modulo) {
		// 	dump( $modulo->modulos );
		// }

		\View::share('menu', $modulos);

		return $next($request);
	}
}
