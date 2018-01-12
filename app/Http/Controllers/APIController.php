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
    //joins:[
            {"leftJoin":
                ["fac_opr_facturas_clientes as fc","fc.id_factura","=","fac_det_facturas_clientes.fk_id_factura"
            ]},
            {"join":
                ["pry_cat_clave_cliente_productos as cc","cc.id_clave_cliente_producto","=","fac_det_facturas_clientes.fk_id_clave_cliente"
            ]}],
	limit: 5,
	//pluck: ['pais', 'id_pais']
	//only: ['pais']
	//"append": ["direccion_concat"]
}, function(response){
	//console.log(response)
})
*/

class APIController extends Controller
{
	public function index($company, $entity)
	{
		$str_json = '{'.Crypt::decryptString(request()->param_js).'}';
		$param_array = request()->all();
		$json = str_replace(array_keys($param_array),$param_array,$str_json);

        $request = json_decode($json,true);
		# Obtenemos entidad
        
        $class = 'App\\Http\\Models\\' . implode('\\', array_map('ucwords', explode('.', camel_case($entity))));
        $entity = new $class;
        
        if ($entity) {

            # Si hay JOINS
            foreach (($request['joins'] ?? []) as $joins){
                foreach ($joins as $join => $args){
                    $entity = call_user_func_array([$entity, $join], $args);
                }
            }

            # Select especific fields
		    $entity = call_user_func_array([$entity, 'select'], $request['select'] ?? []);
            if(isset($request['distinct'])) {
                $entity = call_user_func_array([$entity, 'distinct'], $request['distinct'] ?? []);
            }
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

			$collections = $entity->get();

			# Pluck collection
			if (isset($request['pluck'])) {
			    $collections = call_user_func_array([$collections, 'pluck'], $request['pluck']);
			}

			if (isset($request['append'])) {
				$collections = $collections->map->append($request['append']);
			}

			if (isset($request['only'])) {
				$collections = $collections->map->only($request['only']);
			}

			return $collections;
		}
	}
}