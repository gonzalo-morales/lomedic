<?php

namespace App\Http\Controllers;
use DB;
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
		
		// $r = $this->entity->where('eliminar', '=','0')->orderby($this->entity->getKeyName(),'ASC')->paginate(20);
		// return \Response::JSON($r);
		
		$query = $this->entity->orderby($this->entity->getKeyName(),'ASC')->limit(500);
		
		if(isset($attributes['where'])) {
		    foreach ($attributes['where'] as $key=>$condition) {
		        $query->where(DB::raw($condition));
		    }
		}
		
		$data = $query->get();
		$fields = $this->entity->getFields();

		return view(currentRouteName('smart'), compact('fields','data');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company)
	{
		# ¿Usuario tiene permiso para crear?
		$this->authorize('create', $this->entity);

		return view(currentRouteName('smart'), [
			'data' => $this->entity->ColumnDefaultValues(),
		]);
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
	public function show($company, $id)
	{
		# ¿Usuario tiene permiso para ver?
		$this->authorize('view', $this->entity);

		# Log
		$this->log('show', $id);

		return view(currentRouteName('smart'), [
			'data' => $this->entity->findOrFail($id),
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($company, $id)
	{
		# ¿Usuario tiene permiso para actualizar?
		$this->authorize('update', $this->entity);

		return view(currentRouteName('smart'), [
			'data' => $this->entity->findOrFail($id),
		]);
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
		# ¿Usuario tiene permiso para descargar?
		// $this->authorize('download', $this->entity);

		if (!$request->download && $request->method() == 'POST') {

			sleep(2);

			return response()->json([
				'success' => true,
				'url' => 'http://localhost:8000/abisa/administracion/bancos/export?type=CSV&download=1',
			]);

		} else {

			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=savethis.txt");

			print "This is some text...\n";

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