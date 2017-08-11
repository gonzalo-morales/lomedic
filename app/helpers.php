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
function companyAction($action = '', $params = [])
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
			$autoparams['id'] =  head($_params);
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
 * Obtenemos arreglo de nombres de ruta actual
 * @param  string $route - nombre por el que reemplazar
 * @return array
 */
function routeNameReplace($route = '')
{
	return array_map(function($current, $expected) {
		return $expected === '' ? $current : $expected;
	}, explode('.', Route::currentRouteName()), array_pad(explode('.', $route, 3), -3, '') );
}

/**
 * Obtenemos URL de ruta
 * @param  string $route - Acción por la que reemplazar
 * @param  array  $params - Parametros personalizados
 * @return string
 */
function companyRoute($route = '', $params = [])
{
	#
	$expected_action = routeNameReplace($route);

	# Injectamos empresa
	$autoparams = ['company' => request()->company];

	# Injectamos Id
	if (in_array(last($expected_action), ['show', 'edit', 'update', 'destroy'])) {
		# Obtenemos parametros, omitiendo empresa
		$_params = array_except(request()->route()->parameters, ['company']);
		if (!empty($_params)) {
			# Asiganmos parametro, que por logica debe ser el Id
			$autoparams['id'] =  head($_params);
		}
	}

	# Generamos URL
	return route(implode('.', $expected_action), array_merge(
		$autoparams, $params
	));
}

/**
 * Obtenemos nombre de ruta actual
 * @param  string $route - nombre por el que reemplazar
 * @return string
 */
function currentRouteName($route = '')
{
	return implode('.', routeNameReplace($route));
}

/**
 * Obtenemos entidad
 * @return Entity
 */
function currentEntity()
{
	return Route::getCurrentRoute()->getController()->entity;
}

/**
 * Obtenemos nombre completo de la entidad
 * @return string
 */
function currentEntityName()
{
	return get_class(currentEntity());
}

/**
 * Obtenemos nombre base de la entidad
 * @return string
 */
function currentEntityBaseName()
{
	return class_basename(currentEntityName());
}
