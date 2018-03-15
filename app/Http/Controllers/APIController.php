<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use function foo\func;
use Illuminate\Support\Facades\Crypt;

/*
$.get('http://localhost:8000/abisa/administracion.paises/api', {
	select: ['id_pais', 'pais'],
	//conditions: [{'where': ['id_pais','42']},{'where':['pais','MÉXICO']}]
	//conditions: [{'whereIn':['id_pais',[5,42]]}],
	//conditions: [{'where':['pais','like','Argen%']}],
	//with: ['estados:id_estado,fk_id_pais,estado'],
    //has: ['estados'],
    //"whereHas": [{"stock":{"where":["fk_id_almacen", "$fk_id_almacen"]}}]
	//whereHas: [{'estados':{[{'where':['fk_id_pais', 42]},{'cwhereHas':[municipio:{'where':['id_municipio',5]}]}]}}]
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
        // dd($request);
		# Obtenemos entidad
        $controllers = controllerByRoute();
        $controllerName = substr($controllers[$entity.'.index'],0,-6);
        
        $controller = new $controllerName;
        $class = $controller->entity;
        
        #$class = 'App\\Http\\Models\\' . implode('\\', array_map('ucwords', explode('.', camel_case($entity))));
        
        $entity = new $class;
        if ($entity) {
            # Si hay JOINS
            foreach (($request['joins'] ?? []) as $joins){
                foreach ($joins as $join => $args){
                    $entity = call_user_func_array([$entity, $join], $args);
                }
            }
            # Select especific fields
            if(isset($request['selectRaw'])){//Revisa primero si hay un selectRaw
                $entity = call_user_func_array([$entity, 'selectRaw'], $request['selectRaw'] ?? []);
            }else{//Si no hay selectRaw, busca select normal
                $entity = call_user_func_array([$entity, 'select'], $request['select'] ?? []);
            }
            if(isset($request['distinct'])) {
                $entity = call_user_func_array([$entity, 'distinct'], $request['distinct'] ?? []);
            }
			# Si hay eagerloaders
            foreach (($request['withFunction'] ?? []) as $relations) {
                foreach ($relations as $relation => $conditions) {
                    $entity = $entity->with([$relation => function($query) use($conditions) {
                        foreach ($conditions as $condition => $args) {
                            // dump($args);
                            call_user_func_array([$query, $condition], $args);
                        }
                    }]);
                }
            }
            // exit();
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
            if(isset($request['whereHas'])){
                foreach (($request['whereHas'] ?? []) as $relations) {
                    foreach ($relations as $relation => $conditions) {
                        $entity = $entity->whereHas($relation, function($query) use($conditions) {
                            foreach ($conditions as $condition => $args) {
                                if($condition == 'cwhereHas') {
                                    $argumento = $args[0];
                                    $query->whereHas(array_keys($argumento)[0],function ($item) use ($argumento){
                                        foreach ($argumento[array_keys($argumento)[0]][0] as $condition => $value){
                                            call_user_func_array([$item,$condition],[$value]);
                                        }
                                    });
                                }
                                else
                                    call_user_func_array([$query, $condition], $args);
                                
                            }
                        });
                    }
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

			if(isset($request['pivot'])){
			    foreach ($collections as $collection){
                    foreach ($request['pivot'] as $model){
                        $collections = $collection->{$model}->map(function ($item){
                           return $item->pivot;
                        });
                    }
                }
            }

			return $collections;
		}
	}

}