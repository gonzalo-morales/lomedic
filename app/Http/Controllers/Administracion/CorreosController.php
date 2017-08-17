<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Correos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Logs;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CorreosController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Correos $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
        $this->users = Usuarios::all();
        $this->company = request()->company;
        $this->companies = Empresas::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    // public function index($company,  $attributes = [])
    // {
    //     Logs::createLog($this->entity->getTable(),$company,null,'index',null);

    //     return view(Route::currentRouteName(), [
    //         'entity' => $this->entity_name,
    //         'data' => $this->entity->all()->where('eliminar', '=','0'),
    //     ]);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company, $attributes = [])
    {
        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'users' => $this->users,
            'company' => $this->company,
            'companies' => $this->companies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$company)
    {
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

//		$created = $this->entity->create($request->all());

        $this->entity->fill($request->all());

        if($this->entity->save())
        {Logs::createLog($this->entity->getTable(),$company,$this->entity->id_correo,'crear','Registro insertado');}
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
    public function show($company,$id, $attributes = [])
    {
        $fk_id_usuario = $this->entity->findOrFail($id)->fk_id_usuario;
        $fk_id_empresa = $this->entity->findOrFail($id)->fk_id_empresa;

        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'data' => $this->entity->findOrFail($id),
            'user' => $this->users->find($fk_id_usuario)->usuario,
            'empresa' => $this->companies->find($fk_id_empresa)->nombre_comercial,
            'company' => $company,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($company,$id, $attributes = [])
    {
        $fk_id_usuario = $this->entity->findOrFail($id)->fk_id_usuario;
        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'data' => $this->entity->findOrFail($id),
            'users' => $this->users,
            'companies' => $this->companies,
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer	$id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$company, $id)
    {
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        if($entity->save())
        {Logs::createLog($this->entity->getTable(),$company,$id,'editar','Registro actualizado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,$id,'editar','Error al editar');}
        /*$request->input('nombre')*/
        # Redirigimos a index
        return redirect(companyRoute('index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer 	$id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $company, $idOrIds)
    {
        /*$entity = $this->entity->findOrFail($id);
        $entity->delete();*/
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
