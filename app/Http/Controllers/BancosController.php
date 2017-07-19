<?php

namespace App\Http\Controllers;

use App\Http\Models\Bancos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class BancosController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Bancos $entity)
	{
		// $this->middleware('auth');
		$this->entity = $entity;
		$this->entity_name = strtolower(class_basename($entity));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'data' => $this->entity->all(),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$created = $this->entity->create($request->all());

		# Redirigimos a index
		return redirect()->route("$this->entity_name.index")->with('success', trans_choice('messages.'.$this->entity_name, 0) .', creado con exito.');
	}

	/**
	 * Display the specified resource
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return view (Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'data' => $this->entity->findOrFail($id),
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return view (Route::currentRouteName(), [
			'entity' => $this->entity_name,
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
	public function update(Request $request, $id)
	{
		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		$entity->save();

		# Redirigimos a index
		return redirect()->route("$this->entity_name.index")->with('success', trans_choice('messages.'.$this->entity_name, 0) .', actualizado con exito.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  integer 	$id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$entity = $this->entity->findOrFail($id);
		$entity->delete();

		# Redirigimos a index
		return redirect()->route("$this->entity_name.index")->with('success', trans_choice('messages.'.$this->entity_name, 0) .', borrado con exito.');
	}
}
