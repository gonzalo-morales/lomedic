<?php

/**
 * Obtenemos arreglo accion de ruta personalizada
 * @param  string $action - Acción por la que reemplazar
 * @return array
 */
function routeActionReplace($action = '')
{
	# RouteAction actual, ej: HomeController@index
	$current_action = str_replace('App\Http\Controllers\\', '', Route::currentRouteAction());
	#
	return array_map(function($current, $expected) {
		return $expected === '' ? $current : $expected;
	}, explode('@', $current_action), array_pad(explode('@', $action, 2), -2, '') );
}

/**
 * Obtenemos URL de accion
 * @param  string $action - Acción por la que reemplazar
 * @param  array  $params - Parametros personalizados
 * @return string
 */
function companyRoute($action = '', $params = [])
{
	#
	$expected_action = routeActionReplace($action);

	# Injectamos empresa
	$autoparams = ['company' => request()->company];

	# Injectamos Id
	if (in_array($expected_action[1], ['show', 'edit', 'update', 'destroy'])) {
		# Obtenemos parametros, omitiendo empresa
		$_params = array_except(request()->route()->parameters, ['company']);
		if (!empty($_params)) {
			# Asiganmos parametro, que por logica debe ser el Id
			$autoparams['id'] =  array_first($_params);
		}
	}

	# Generamos URL
	return action(implode('@', $expected_action), array_merge(
		$autoparams, $params
	));
}

/**
 * Obtenemos accion de ruta actual
 * @param  string $action - Acción por la que reemplazar
 * @return string
 */
function currentRouteAction($action = '')
{
	return implode('@', routeActionReplace($action));
}

/**
 * Obtenemos modelo asociado a controlador
 * @return Model
 */
function currentRouteModel()
{
	$action = explode('@', Route::currentRouteAction());
	$model = str_replace('Controller', '', str_replace('Controllers', 'Models', $action[0]));
	return new $model;
}
