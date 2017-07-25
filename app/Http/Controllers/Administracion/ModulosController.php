<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Modulos;
use App\Http\Models\Administracion\Empresas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModulosController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Modulos $entity)
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
		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'data' => $this->entity->all(),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Empresas $empresas, $company)
	{
		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'empresas' => $empresas->all(),
			'modulos' => $this->entity->all(),
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
		$this->validate($request, $this->entity->rules());

		$created = $this->entity->create($request->all());
		$created->modulos()->sync($request->modulos);
		$created->empresas()->sync($request->empresas);

		# Redirigimos a index
		return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', creado con exito.');
	}

	/**
	 * Display the specified resource
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Empresas $empresas, $company, $id)
	{
		return view (Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'data' => $this->entity->findOrFail($id),
			'empresas' => $empresas->all(),
			'modulos' => $this->entity->all(),
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Empresas $empresas, $company, $id)
	{
		return view (Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'data' => $this->entity->findOrFail($id),
			'empresas' => $empresas->all(),
			'modulos' => $this->entity->all(),
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
		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules($id));

		$entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		$entity->save();

		$entity->modulos()->sync($request->modulos);
		$entity->empresas()->sync($request->empresas);

		# Redirigimos a index
		return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', actualizado con exito.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  integer  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($company, $id)
	{
		$entity = $this->entity->findOrFail($id);
        $entity->fk_id_usuario_elimina = Auth::id();//Usuario que elimina el registro
        $entity->fecha_elimina = DB::raw('now()');//Fecha y hora de la eliminación
        $entity->eliminar='t';
        $entity->save();
//		$entity->empresas()->detach($entity->empresas);
//		$entity->delete();

		# Redirigimos a index
		return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', borrado con exito.');
	}
}
