<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Logs;
use App\Http\Models\Soporte\ArchivosAdjuntos;
use App\Http\Models\Soporte\SeguimientoSolicitudes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use App\Http\Models\Soporte\Solicitudes;
use App\Http\Models\Soporte\EstatusTickets;

class SeguimientoSolicitudesController extends ControllerBase
{

    public function __construct()
    {
        $this->entity = new SeguimientoSolicitudes;
    }

    public function store(Request $request, $company, $compact = false) // Para crear un nuevo ticket
    {
        $this->validate($request, $this->entity->rules);
        
        $id_estatus_ticket = $request->request->get('fk_id_estatus_ticket',null);
        
        if(!empty($id_estatus_ticket)) {
            $estatus = EstatusTickets::findOrFail($id_estatus_ticket);
            
            $asunto = $request->request->get('asunto','');
            $request->request->set('asunto',$asunto.". (Cambio estatus: $estatus->estatus)");
        }
        
        $created = $this->entity->create($request->all());
        
        if ($created) {
            if(!empty($id_estatus_ticket)) {
                Solicitudes::where('id_solicitud', $created->fk_id_solicitud)->update(['fk_id_estatus_ticket' => $id_estatus_ticket]);
            }
            
            $files = Input::file('archivo');
            if (Input::hasFile('archivo')) {
                foreach ($files as $file) {
                    $path = public_path() . '\storage\\' . $company . '\ticket' . $created->fk_id_solicitud;
                    $filename = uniqid() . $file->getClientOriginalName();
                    $file->move($path, $filename);
                    $archivo_adjunto = new ArchivosAdjuntos();
                    $archivo_adjunto->fk_id_solicitud = $created->fk_id_solicitud;
                    $archivo_adjunto->ruta_archivo = $path;
                    $archivo_adjunto->nombre_archivo = $filename;
                    $archivo_adjunto->fk_id_mensaje = $created->id_seguimiento;
                    $archivo_adjunto->save();
                }
            }
            
            Logs::createLog($this->entity->getTable(), $company, $created->id_solicitud, 'crear', 'Ticket creado');
        } else {
            Logs::createLog($this->entity->getTable(), $company, null, 'crear', 'Error al guardar mensaje');
        }
        
        return redirect(URL::previous());
    }
}
