<?php

namespace App\Http\Controllers;
use DB;
use Excel;
use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

class ControllerBase extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($company, $attributes = ['where'=>['eliminar = 0']])
	{
		# ¿Usuario tiene permiso para ver?
		$this->authorize('view', $this->entity);

		# Log
		$this->log('index');

		$query = $this->entity->orderBy($this->entity->getKeyName(),'DESC');

		if(isset($attributes['where'])) {
    		foreach ($attributes['where'] as $key=>$condition) {
    			$query->where(DB::raw($condition));
    		}
		}
		
		$dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];
		
		if (!request()->ajax()) {
		    return view(currentRouteName('smart'), $dataview+[
				'fields' => $this->entity->getFields(),
				'data' => $query->limit(20)->get(),
			]);

		# Ajax
		} else {
		    $data = $query->paginate(4000);
			if( request()->page && request()->page == 1) {
				$data->setCollection($data->getCollection()->slice(20));
			}
			return response()->json($data);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company, $attributes =[])
	{
		# ¿Usuario tiene permiso para crear?
		$this->authorize('create', $this->entity);
		$data = $this->entity->ColumnDefaultValues();
		$dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];

		return view(currentRouteName('smart'), $dataview+['data'=>$data]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, $company)
	{
		# ¿Usuario tiene permiso para crear?
		$this->authorize('create', $this->entity);

		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$isSuccess = $this->entity->create($request->all());
		if ($isSuccess) {
			$this->log('store', $isSuccess->id_banco);
			return $this->redirect('store');
		} else {
			$this->log('error_store');
			return $this->redirect('error_store');
		}
	}

	/**
	 * Display the specified resource
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($company, $id, $attributes =[])
	{
		# ¿Usuario tiene permiso para ver?
		$this->authorize('view', $this->entity);

		# Log
		$this->log('show', $id);
		$data = $this->entity->findOrFail($id);
		$dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];

		return view(currentRouteName('smart'), $dataview+['data'=>$data]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($company, $id, $attributes =[])
	{
		# ¿Usuario tiene permiso para actualizar?
		$this->authorize('update', $this->entity);
		$data = $this->entity->findOrFail($id);
		$dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];

		return view(currentRouteName('smart'), $dataview+['data'=>$data]);
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
		# ¿Usuario tiene permiso para actualizar?
		$this->authorize('update', $this->entity);

		# Validamos request, si falla regresamos atras
		$this->validate($request, $this->entity->rules);

		$entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		if ($entity->save()) {
			$this->log('update', $id);
			return $this->redirect('update');
		} else {
			$this->log('error_update', $id);
			return $this->redirect('error_update');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  integer  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $company, $idOrIds)
	{
		# ¿Usuario tiene permiso para eliminar?
		$this->authorize('delete', $this->entity);

		# Unico
		if (!is_array($idOrIds)) {

			$isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)->update(['eliminar' => 't']);
			if ($isSuccess) {

				$this->log('destroy', $idOrIds);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => true,
					]);
				} else {
					return $this->redirect('destroy');
				}

			} else {

				$this->log('error_destroy', $idOrIds);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => false,
					]);
				} else {
					return $this->redirect('error_destroy');
				}
			}

		# Multiple
		} else {

			$isSuccess = $this->entity->whereIn($this->entity->getKeyName(), $idOrIds)->update(['eliminar' => 't']);
			if ($isSuccess) {

				# Shorthand
				foreach ($idOrIds as $id) $this->log('destroy', $id);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => true,
					]);
				} else {
					return $this->redirect('destroy');
				}

			} else {

				# Shorthand
				foreach ($idOrIds as $id) $this->log('error_destroy', $id);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => false,
					]);
				} else {
					return $this->redirect('error_destroy');
				}
			}
		}
	}

	/**
	 * Remove multiple resources from storage.
	 * @param  Request $request
	 * @param  string  $company
	 * @return \Illuminate\Http\Response
	 */
	public function destroyMultiple(Request $request, $company)
	{
		# ¿Usuario tiene permiso para eliminar?
		$this->authorize('delete', $this->entity);

		# Shorthand
		if ($request->ids) return $this->destroy($request, $company, $request->ids);

		return response()->json([
			'success' => false,
		]);
	}

	/**
	 * Obtenemos reporte
	 * @param  string $company
	 * @return file
	 */
	public function export(Request $request, $company)
	{
		# ¿Usuario tiene permiso para exportar?
		$this->authorize('export', $this->entity);
		$type = strtolower($request->type);
		$style = isset($request->style) ? $request->style : false;

	    if (isset($request->ids)) {
	        $ids = is_array($request->ids) ? $request->ids : explode(',',$request->ids);
	        $data = $this->entity->whereIn($this->entity->getKeyName(), $ids)->get();
		}
		else {
		    $data = $this->entity->get();
		}
		
		$fields = $this->entity->getFields();
		
		$alldata = $data->map(function ($data) use($fields) {
		    $return = [];
		    foreach ($fields as $field=>$lable)
		        $return[$lable] = html_entity_decode(strip_tags($data->$field));
		    return $return;
		});
		
		if($type == 'pdf') {
		    $pdf = PDF::loadView(currentRouteName('smart'), ['fields' => $fields, 'data' => $data]);
		    return $pdf->stream(currentEntityBaseName().'.pdf')->header('Content-Type',"application/$type");
		}
		else {
		    Excel::create(currentEntityBaseName(), function($excel) use($data,$alldata,$type,$style) {
		        $excel->sheet(currentEntityBaseName(), function($sheet) use($data,$alldata,$type,$style) {
    		        if($style) {
    		            $sheet->loadView(currentRouteName('smart'), ['fields' => $this->entity->getFields(), 'data' => $data]);
    		        }
    		        else
    		            $sheet->fromArray($alldata);
    	        });
		    })->download($type);
		}
	}

	/**
	 * Insertamos log
	 * @param  string $type
	 * @param  integer $id
	 * @return void
	 */
	public function log($type, $id = null)
	{
		switch ($type) {
			case 'index':
				Logs::createLog($this->entity->getTable(), request()->company, null, 'index', null);
				break;

			case 'show':
				Logs::createLog($this->entity->getTable(), request()->company, $id, 'ver', null);
				break;

			case 'store':
				Logs::createLog($this->entity->getTable(), request()->company, $id, 'crear', 'Registro insertado');
				break;

			case 'error_store':
				Logs::createLog($this->entity->getTable(), request()->company, null, 'crear', 'Error al insertar');
				break;

			case 'update':
				Logs::createLog($this->entity->getTable(), request()->company, $id, 'editar', 'Registro actualizado');
				break;

			case 'error_update':
				Logs::createLog($this->entity->getTable(), request()->company, $id, 'editar', 'Error al editar');
				break;

			case 'destroy':
				Logs::createLog($this->entity->getTable(), request()->company, $id, 'eliminar', 'Registro eliminado');
				break;

			case 'error_destroy':
				Logs::createLog($this->entity->getTable(), request()->company, $id, 'eliminar', 'Error al eliminar');
				break;

			default:
				break;
		}
	}

	public function redirect($type)
	{
		switch ($type) {
			case 'store':
				$message = ['type'=> 'toast_success', 'text' => 'Registro creado correctamente.'];
				break;

			case 'error_store':
				$message = ['type'=> 'toast_error', 'text' => 'No fue posible crear registro.'];
				break;

			case 'update':
				$message = ['type'=> 'toast_success', 'text' => 'Registro actualizado correctamente.'];
				break;

			case 'error_update':
				$message = ['type'=> 'toast_error', 'text' => 'No fue posible actualizar registro.'];
				break;

			case 'destroy':
				$message = ['type'=> 'toast_success', 'text' => 'Registro (s) eliminado correctamente.'];
				break;

			case 'error_destroy':
				$message = ['type'=> 'toast_error', 'text' => 'No fue posible eliminar registro (s).'];
				break;

			default:
				break;
		}

		return redirect(companyRoute('index'))->with('message', $message);
	}
}