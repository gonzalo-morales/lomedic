<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Unidadesmedicas;
use App\Http\Models\Administracion\Modulos;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Administracion\Empresas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;
class UnidadesMedicasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Unidadesmedicas $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
        $this->company = request()->company;
        $this->users = Usuarios::all();
        $this->modules = Modulos::all();
        $this->empresas = Empresas::all();
        $this->logs = Logs::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company)
    {

        Logs::createLog($this->entity->getTable(),$company,null,'index','index');

        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $this->company,
            'data' => $this->entity->all()->where('eliminar', '=',false),
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
            'company' => $this->company,
            'users' => $this->users,
            'modules' => $this->modules,
            'empresas' => $this->empresas
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
        //$this->validate($request, $this->entity->rules);

        $created = $this->entity->create($request->all());
        if($created)
        {Logs::createLog($this->entity->getTable(),$company,$created->id_perfil,'crear','Registro insertado');}
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
    public function show($company,$id)
    {
        Logs::createLog($this->entity->getTable(),$company,$id,'ver','Registro visto');

        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'data' => $this->entity->findOrFail($id),
            'company' => $this->company,
            'users' => $this->users,
            'empresas' => $this->empresas,
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
            'data' => $this->entity->findOrFail($id),
            'company' => $this->company,
            'users' => $this->users,
            'empresas' => $this->empresas
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer	$id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$company ,$id)
    {
        # Validamos request, si falla regresamos pagina


        //$this->validate($request, $this->entity->rules);


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
    public function destroy($company,$id)
    {
        $entity = $this->entity->findOrFail($id);
        $entity->eliminar='t';
        if($entity->save())
        {Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al eliminar');}
        $entity->save();

        # Redirigimos a index
        return redirect(companyRoute('index'));
    }
}
