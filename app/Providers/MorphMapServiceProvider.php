<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Http\Models\Administracion\TiposDocumentos;
use File;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 * @return void
	 */
	public function boot()
	{
		$models = [];

		foreach(File::allFiles(app_path().'/Http/Models') as $route) {
		    if(preg_match("/^.*.php$/", $route->getPathname())){
		        $model = substr(str_replace([base_path().'\a','/'],['A','\\'],$route->getPathname()),0,-4);
		        $tipo_documento = TiposDocumentos::where('tabla',(new $model)->getTable())->first();
		        
		        if(isset($tipo_documento->id_tipo_documento))
		            $models[$tipo_documento->class] = new $model;
		    }
		}
		
		Relation::morphMap(['PedidosDetalle'=>'\App\Http\Models\Ventas']);
	}
}