<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\MotivosNotas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

class MotivosNotasController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(MotivosNotas $entity)
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
		Logs::createLog($this->entity->getTable(),$company,null,'index',null);

		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'data' => $this->entity->all()->where('eliminar','0'),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company)
	{
		return view(Route::currentRouteName(), [
			'entity'  => $this->entity_name,
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
		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$created = $this->entity->create([
			'motivo' 	=> $request->motivo,
			'tipo' 		=> $request->tipo,
			'activo'	=> $request->activo,
		]);
		if($created){
			Logs::createLog($this->entity->getTable(),$company,$created->id_motivo,'crear','Registro insertado');
		}else{
			Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al insertar');
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
		Logs::createLog($this->entity->getTable(),$company,$id,'ver',null);

		return view (Route::currentRouteName(), [
			'entity' 	=> $this->entity_name,
			'company' 	=> $company,
			'data' 		=> $this->entity->findOrFail($id),
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
		return view (Route::currentRouteName(), [
			'entity' 	=> $this->entity_name,
			'company' 	=> $company,
			'data' 		=> $this->entity->findOrFail($id),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  integer	$id
	 * @return \Illuminate\Http\Response
	 *
	 */
	public function update(Request $request, $company, $id)
	{
		$this->validate($request, $this->entity->rules);

		$entity = $this->entity->findOrFail($id);
		$entity->fill([
			'motivo' 	=> $request->motivo,
			'tipo'     	=> $request->tipo,
			'activo'  	=> $request->activo,
		]);
		if($entity->save()){
			Logs::createLog($this->entity->getTable(),$company,$id,'editar','Registro actualizado');
		}else{
			Logs::createLog($this->entity->getTable(),$company,$id,'editar','Error al editar');
		}

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
		$entity 			= $this->entity->findOrFail($id);
		$entity->eliminar 	= 't';
		if($entity->save()){
			Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');
		}else{
			Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al eliminar');
		}

		return redirect(companyRoute('index'));
	}
}
