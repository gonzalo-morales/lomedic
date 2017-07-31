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

		Logs::createLog($this->entity->getTable(),$company,null,'index',null);

		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'data' => $this->entity->all()->where('eliminar', '=','0'),
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

		$created = $this->entity->create($request->all());
		if($created)
		{Logs::createLog($this->entity->getTable(),$company,$created->id_banco,'crear','Registro insertado');}
		else
		{Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al insertar');}

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

		Logs::createLog($this->entity->getTable(),$company,$id,'ver',null);

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
	 * @param  integer	$id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $company, $id)
	{
		# ¿Usuario tiene permiso para actualizar?
		$this->authorize('update', $this->entity);

		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		if($entity->save())
		{Logs::createLog($this->entity->getTable(),$company,$id,'editar','Registro actualizado');}
		else
		{Logs::createLog($this->entity->getTable(),$company,$id,'editar','Error al editar');}

		# Redirigimos a index
		return redirect(companyRoute('index'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  integer 	$id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($company, $id)
	{
		# ¿Usuario tiene permiso para eliminar?
		$this->authorize('delete', $this->entity);

		$entity = $this->entity->findOrFail($id);
		$entity->eliminar='t';
		if($entity->save())
		{Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');}
		else
		{Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al eliminar');}

		# Redirigimos a index
		return redirect(companyRoute('index'));
	}
}
