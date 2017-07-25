<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Sucursales;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Auth;
use DB;
class SucursalesController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Sucursales $entity)
	{
		$this->middleware('auth');
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
			'data' => $this->entity->all()->where('eliminar', '=','0'),
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

//		$created = $this->entity->create($request->all());

        $this->entity->fill($request->all());
        $this->entity->fk_id_usuario_crea = Auth::id();
        $this->entity->save();


        /*$request->input('nombre')*/

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

//		$created = $this->entity->create($request->all());

        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        $entity->fk_id_usuario_actualiza = Auth::id();//Usuario que actualiza el registro
        $entity->fecha_actualiza = DB::raw('now()');//Fecha y hora de la actualización
        $entity->save();


        /*$request->input('nombre')*/

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
		/*$entity = $this->entity->findOrFail($id);
		$entity->delete();*/
        $entity = $this->entity->findOrFail($id);
        $entity->fk_id_usuario_elimina = Auth::id();//Usuario que elimina el registro
        $entity->fecha_elimina = DB::raw('now()');//Fecha y hora de la eliminación
        $entity->eliminar='t';
        $entity->save();

		# Redirigimos a index
		return redirect()->route("$this->entity_name.index")->with('success', trans_choice('messages.'.$this->entity_name, 0) .', borrado con exito.');
	}
}
