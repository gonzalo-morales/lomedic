<?php
namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
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
		
	    Collection::macro('utf8_encode', function() {
	        return collect($this->items)->map(function($word) {
	            return utf8_encode($word);
	        });
	    });
	    
        Collection::macro('utf8_decode', function() {
            return collect($this->items)->map(function($word) {
                return utf8_decode($word);
            });
        });

		// HTML Components
		require_once app_path().'/components.php';
		
		Blade::if('index', function() {
		    return \Route::currentRouteNamed(currentRouteName('index'));
		});
	    Blade::if('crear', function() {
	        return \Route::currentRouteNamed(currentRouteName('create'));
	    });
        Blade::if('editar', function() {
            return \Route::currentRouteNamed(currentRouteName('edit'));
        });
        Blade::if('ver', function() {
            return \Route::currentRouteNamed(currentRouteName('show'));
        });
        Blade::if('exportar', function() {
            return \Route::currentRouteNamed(currentRouteName('export'));
        });
        
        Blade::if('inroute', function($routes = []) {
            foreach($routes as $route) {
                if(\Route::currentRouteNamed(currentRouteName($route)))
                    return true;
            }
            return false;
        });
        
        Blade::if('notroute', function($routes = []) {
            foreach($routes as $route) {
                if(\Route::currentRouteNamed(currentRouteName($route)))
                    return false;
            }
            return true;
        });
	}

	/**
	 * Register any application services.
	 * @return void
	 */
	public function register()
	{
	    require_once app_path().'/helpers.php';
	    require_once app_path().'/cfdi.php';
	}
}