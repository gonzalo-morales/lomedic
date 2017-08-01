<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Modulos;
use App\Http\Models\Administracion\Empresas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

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
        Logs::createLog($this->entity->getTable(),$company,null,'index',null);
		return view(Route::currentRouteName(), [
			'entity' => $this->entity_name,
			'company' => $company,
			'data' => $this->entity->all()->where('eliminar',0),
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

        if($created)
        {
            Logs::createLog($this->entity->getTable(),$company,$created->id_modulo,'crear','Registro insertado');
            $created->modulos()->sync($request->modulos);
            $created->empresas()->sync($request->empresas);
        }
        else
        {
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
	public function show(Empresas $empresas, $company, $id)
	{
        Logs::createLog($this->entity->getTable(),$company,$id,'ver',null);
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
		if($entity->save())
        {
            Logs::createLog($this->entity->getTable(),$company,$id,'editar','Registro actualizado');
            $entity->modulos()->sync($request->modulos);
            $entity->empresas()->sync($request->empresas);
        }else
        {
            Logs::createLog($this->entity->getTable(),$company,$id,'editar','Error al editar');
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
	public function destroy($company, $id)
	{
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
