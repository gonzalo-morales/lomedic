<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Administracion\Bancos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;
use App\Http\Models\Administracion\Empresas;

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
//        dump(Empresas::where('nombre_comercial', strtoupper($company))->get());

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
        # Validamos request, si falla regresamos pagina
        //$this->validate($request, $this->entity->rules);

        $created = $this->entity->create($request->all());

        Logs::createLog($this->entity->getTable(),$created->id_banco,$company);

		# Redirigimos a index
		    return redirect()->companyRoute("index")->with('success', trans_choice('messages.'.$this->entity_name, 0) .', creado con exito.');
	}

	/**
	 * Display the specified resource
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($company, $id)
	{
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
		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		$entity->save();
        Logs::editLog($this->entity->getTable(),$company,$id);
//		Logs::create([
//			'table' => $this->entity->getTable();
//			'fk_id_usuario' => Auth::user()
//			'acction' =>
//			'table' =>
//		])


		# Redirigimos a index
		return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', actualizado con exito.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  integer 	$id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($company, $id)
	{
		$entity = $this->entity->findOrFail($id);
        $entity->eliminar='t';
        $entity->save();
//		$entity->delete();
        Logs::deleteLog($this->entity->getTable(),$company,$id);

		# Redirigimos a index
		return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', borrado con exito.');
	}
}
