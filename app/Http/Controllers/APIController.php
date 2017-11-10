<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

/*
$.get('http://localhost:8000/abisa/administracion.paises/api', {
	select: ['id_pais', 'pais'],
	//conditions: [{'where': ['id_pais','42']},{'where':['pais','MÉXICO']}]
	//conditions: [{'whereIn':['id_pais',[5,42]]}],
	//conditions: [{'where':['pais','like','Argen%']}],
	//with: ['estados:id_estado,fk_id_pais,estado'],
	//has: ['estados'],
	//whereHas: [{'estados':{'where':['fk_id_pais', 42]}}]
	//orderBy: [['id_pais', 'DESC']],
	limit: 5,
	//pluck: ['pais', 'id_pais']
}, function(response){
	//console.log(response)
})
*/

class APIController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($company, $entity)
	{
	    $str_json = '{'.Crypt::decryptString(request()->param_js).'}';
	    $param_array = request()->all();

	    $json = str_replace(array_keys($param_array),$param_array,$str_json);

	    $request = json_decode($json,true);

		# Obtenemos entidad
		$entity = rescue(function() use ($entity) {
			return resolve('App\\Http\\Models\\' . implode('\\', array_map('ucwords', explode('.', $entity))));
		});

		if ($entity) {

			# Select especific fields
		    $entity = call_user_func_array([$entity, 'select'], $request['select'] ?? []);

			# Si hay eagerloaders
		    $entity = $entity->with($request['with'] ?? []);

			# Condiciones ... (where, whereIn etc)
		    foreach (($request['conditions'] ?? []) as $conditions) {
				foreach ($conditions as $condition => $args) {
					call_user_func_array([$entity, $condition], $args);
				}
			}

			# Si depende de relacion
			foreach (($request['has'] ?? []) as $relation) {
				$entity = $entity->has($relation);
			}

			# Condiciones de relacion ...
			foreach (($request['whereHas'] ?? []) as $relations) {
				foreach ($relations as $relation => $conditions) {
					$entity = $entity->whereHas($relation, function($query) use($conditions) {
						foreach ($conditions as $condition => $args) {
							call_user_func_array([$query, $condition], $args);
						}
					});
				}
			}

			# Orden de registros
			foreach (($request['orderBy'] ?? []) as $orderBy) {
				call_user_func_array([$entity, 'orderBy'], $orderBy);
			}

			# Limite
			$entity->limit($request['limit'] ?? null);

			#
			$collections = $entity->get();

			# Pluck collection
			if (isset($request['pluck'])) {
			    $collections = call_user_func_array([$collections, 'pluck'], $request['pluck']);
			}

			sleep( rand(1, 5) );

			// dump($collections);
			return $collections;
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company, $attributes =[])
	{
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $company)
	{
	}

	/**
	 * Display the specified resource
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($company, $id, $attributes =[])
	{
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($company, $id, $attributes =[])
	{
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  integer  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $company, $id)
	{
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  integer  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $company, $idOrIds)
	{
	}

	/**
	 * Remove multiple resources from storage.
	 * @param  Request $request
	 * @param  string  $company
	 * @return \Illuminate\Http\Response
	 */
	public function destroyMultiple(Request $request, $company)
	{
	}

	/**
	 * Obtenemos reporte
	 * @param  string $company
	 * @return file
	 */
	public function export(Request $request, $company)
	{
	}
}