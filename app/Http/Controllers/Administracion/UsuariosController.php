<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\Usuarios;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Logs;
use Auth;
use DB;

class UsuariosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Usuarios $entity)
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
            'data' => $this->entity->all()->where('eliminar','=', false),
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
        $this->validate($request, $this->entity->rules);
        $this->entity->fill($request->all());
        $this->entity->password = Hash::make($request->get('password'));
        //$this->entity->fk_id_usuario_crea = Auth::id();
        if($this->entity->save())
        {Logs::createLog($this->entity->getTable(),$company,$this->entity->id_usuario,'crear','Registro creado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al crear');}
        # Redirigimos a index
        //return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', creado con exito.');
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
    public function destroy($company, $id)
    {
        /*
        $entity = $this->entity->findOrFail($id);
        $entity->delete();
        */

        $entity = $this->entity->findOrFail($id);
        //$entity->fk_id_usuario_elimina = Auth::id();//Usuario que elimina el registro
        //$entity->fecha_elimina = DB::raw('now()');//Fecha y hora de la eliminación
        $entity->eliminar='t';
        if($entity->save())
        {Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al editar');}

        # Redirigimos a index
        return redirect(companyRoute('index'));
    }
}
