<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Bancos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

class BancosController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Bancos $entity)
	{
		$this->entity = $entity;
		$this->entity_name = strtolower(class_basename($entity));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($company)
	{
		# ¿Usuario tiene permiso para ver?
		$this->authorize('view', $this->entity);

		# Log
		$this->log('index');

		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'fields' => $this->entity->getFields(),
			'data' => $this->entity->where('eliminar', '=','0')->orderby($this->entity->getKeyName(),'ASC')->get(),
		]);
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

		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
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
		} else {
			$this->log('error_store');
		}

		# Redirigimos a index
		return redirect(companyRoute('index'));
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

		return view (Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
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

		return view (Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
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
		} else {
			$this->log('error_update', $id);
		}

		# Redirigimos a index
		return redirect(companyRoute('index'));
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
					# Redirigimos a index
					return redirect(companyRoute('index'));
				}

			} else {

				$this->log('error_destroy', $idOrIds);

				if ($request->ajax()) {
					# Respuesta Json
					return response()->json([
						'success' => false,
					]);
				} else {
					# Redirigimos a index
					return redirect(companyRoute('index'));
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
					# Redirigimos a index
					return redirect(companyRoute('index'));
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
					# Redirigimos a index
					return redirect(companyRoute('index'));
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
	 * @param  string $status
	 * @param  integer $id
	 * @return void
	 */
	public function log($status, $id = null)
	{
		switch ($status) {
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
}