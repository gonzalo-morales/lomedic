<?php

namespace App\Http\Controllers\Soporte;

//Used models
use App\Http\Controllers\Controller;
use App\Http\Models\Soporte\SeguimientoSolicitudes;
use App\Http\Models\Soporte\Solicitudes;
use App\Http\Models\Soporte\ArchivosAdjuntos;
use App\Http\Models\Logs;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class SeguimientoSolicitudesController extends Controller
{
    public function __construct(SeguimientoSolicitudes $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
    }

    public function store(Request $request, $company)//Para crear un nuevo ticket
    {
        $this->validate($request,$this->entity->rules);
        $created = $this->entity->create($request->all());
        if($created)
            {
                $files = Input::file('archivo');
                if(Input::hasFile('archivo'))
                {
                    foreach ($files as $file){
                        $path = public_path().'\storage\\'.$company.'\ticket'.$created->fk_id_solicitud;//8
                        $filename = uniqid().$file->getClientOriginalName();
                        $file->move($path,$filename);
                        $archivo_adjunto = new ArchivosAdjuntos();
                        $archivo_adjunto->fk_id_solicitud = $created->fk_id_solicitud;
                        $archivo_adjunto->ruta_archivo = $path;
                        $archivo_adjunto->nombre_archivo = $filename;
                        $archivo_adjunto->fk_id_mensaje = $created->id_seguimiento;
                        $archivo_adjunto->save();
                    }
                }

//                Logs::createLog($this->entity->getTable(),$company,$created->id_solicitud,'crear','Ticket creado');
            }
        else
            {Logs::createLog($this->entity->getTable(),$company,null,'crear','Error al guardar mensaje');}

        return redirect(URL::previous());
    }
}
