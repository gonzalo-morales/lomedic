<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Jurisdicciones;
use App\Http\Models\Administracion\Estados;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

class JurisdiccionesController extends Controller
{
    public function __construct(Jurisdicciones $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
        $this->states = Estados::all();
    }

    public function index($company)
    {
        // $this->authorize('view', $this->entity);
        Logs::createLog($this->entity->getTable(),$company,null,'index',null);

        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->all()->where('eliminar', '=','0'),
            'states' => $this->states,
        ]);
    }

    public function create($company)
    {
        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'states' => $this->states,
        ]);
    }

    public function store(Request $request, $company)
    {
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

        $created = $this->entity->create($request->all());
        if($created)
        {Logs::createLog($this->entity->getTable(),$company,$created->id_jurisdiccion,'crear','Registro insertado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al insertar');}

        # Redirigimos a index
        return redirect(companyRoute('index'));
    }

    public function show($company, $id)
    {
        Logs::createLog($this->entity->getTable(),$company,$id,'ver',null);

        $state = $this->entity->findOrFail($id)->fk_id_estado;

        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->findOrFail($id),
            'state' => $this->states->find($state)->estado,
        ]);
    }

    public function edit($company, $id)
    {
        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->findOrFail($id),
            'states' => $this->states,
        ]);
    }

    public function update(Request $request, $company, $id)
    {
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
