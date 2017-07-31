<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\AplicacionesMedicamentos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;

class AplicacionesMedicamentosController extends Controller
{
    public function __construct(AplicacionesMedicamentos $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
    }

    public function index($company)
    {
        // $this->authorize('view', $this->entity);
        Logs::createLog($this->entity->getTable(),$company,null,'index',null);

        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->all()->where('eliminar', '=','0'),
        ]);
    }

    public function create($company)
    {
        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
        ]);
    }

    public function store(Request $request, $company)
    {
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

        $created = $this->entity->create($request->all());
        if($created)
        {Logs::createLog($this->entity->getTable(),$company,$created->id_aplicacion,'crear','Registro insertado');}
        else
        {Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al insertar');}

        # Redirigimos a index
        return redirect(companyRoute('index'));
    }

    public function show($company, $id)
    {
        Logs::createLog($this->entity->getTable(),$company,$id,'ver',null);

        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->findOrFail($id),
        ]);
    }

    public function edit($company, $id)
    {
        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->findOrFail($id),
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
