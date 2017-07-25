<?php

function companyRoute($action = '', $params = [])
{
	# RouteAction actual, ej: HomeController@index
	// $current_action = class_basename(Route::getCurrentRoute()->getActionName());
	$current_action = str_replace('App\Http\Controllers\\','',Route::getCurrentRoute()->getActionName());
	#
	$expected_action = array_map(function($current, $expected) {
		return $expected === '' ? $current : $expected;
	}, explode('@', $current_action), array_pad(explode('@', $action, 2), -2, '') );

	# Injectamos empresa
	$autoparams = ['company' => request()->company];

	# Injectamos Id
	if (in_array($expected_action[1], ['show', 'edit', 'update', 'destroy'])) {
		# Obtenmos nombre del Id
		$param_id = array_last(request()->route()->parameterNames);
		# Asiganmos Id
		$autoparams['id'] =  request()->{$param_id};
	}

	# Generamos URL
	return action(implode('@', $expected_action), array_merge(
		$autoparams, $params
	));
}
