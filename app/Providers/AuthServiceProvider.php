<?php

namespace App\Providers;

use App\Http\Models\Administracion\Permisos;
use App\Http\Models\Administracion\Empresas;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		'App\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot(Permisos $permisos, Empresas $empresas)
	{
		$this->registerPolicies();


		// $empresa = $empresas->where('conexion', 'corporativo')->first();

		// if ($empresa) {
		// 	dump( $empresa->modulos  );
		// }


		// Get the currently authenticated user...
$user = Auth::user();


// Get the currently authenticated user's ID...
$id = Auth::id();


dump( $user, $id );




		// dump( $permisos->all() );
		// Gate::define('some-permiso', function() {
		// 	return $algo ? true : false;
		// });



	}
}
