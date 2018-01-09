<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Handle fix session
		config(['session.expire_on_close' => str_contains(request()->header('User-Agent'), ['MSIE 6.8', 'Windows CE'])]);

		# Extend Route Resource
		$registrar = new \App\Http\ResourceRegistrar($this->app['router']);
		$this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
			return $registrar;
		});

		//
		\Route::resourceVerbs([
			'create' => 'crear',
			'edit' => 'editar',
			// 'impress'=>'imprimir',
		]);

		Collection::macro('reduceWithKeys', function (callable $callback, $initial = null) {
		    $acc = $initial;
		    foreach($this->items as $k => $v) $acc = $callback($acc, $v, $k);
			return $acc;
		});

		// HTML Components
		require_once app_path().'/components.php';
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	    require_once app_path().'/helpers.php';
	    require_once app_path().'/cfdi.php';
	}
}
