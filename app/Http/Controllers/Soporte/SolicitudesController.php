<?php
namespace App\Http\Controllers\Soporte;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Soporte\ArchivosAdjuntos;
use App\Http\Models\Soporte\Categorias;
use App\Http\Models\Soporte\Subcategorias;
use App\Http\Models\Soporte\Acciones;
use App\Http\Models\Soporte\EstatusTickets;
use App\Http\Models\Soporte\Impactos;
use App\Http\Models\Soporte\Solicitudes;
use App\Http\Models\Soporte\Urgencias;
use App\Http\Models\Soporte\SeguimientoSolicitudes;
use App\Http\Models\Soporte\Prioridades;
use App\Http\Models\Logs;
use App;
use DB;
use File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class SolicitudesController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Solicitudes;
        #$this->entity_name = strtolower(class_basename($entity));
    }

    public function getDataView($entity = null)
    {
        $categories_tickets = Categorias::where('activo',1)->pluck('categoria','id_categoria');
        $priorities_tickets = Prioridades::where('activo',1)->pluck('prioridad','id_prioridad');

        return [
            'categories_tickets' => $categories_tickets,
            'priorities_tickets' => $priorities_tickets
        ];
    }
    public function index_tecnicos($company)
    {
        return view(currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->where('fk_id_empleado_tecnico', null)->get()
        ]);
    }

    public function index_tecnico($company)
    {
        $id_empleado = Empleados::findOrFail(Usuarios::where('id_usuario', Auth::id())->first()->fk_id_empleado)->id_empleado;
        
        return view(currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->where('fk_id_empleado_tecnico', $id_empleado)->get()
        ]);
    }

    public function store(Request $request, $company, $compact = false) // Para crear un nuevo ticket
    {
        $id_solicitante = !empty($request->request->get('empleado_solicitud')) ? $request->request->get('empleado_solicitud') : $request->request->get('id_solicitante');
        
        $request->request->set('fk_id_empleado_solicitud', $id_solicitante);
        $request->request->set('fk_id_departamento', Empleados::find($id_solicitante)->first()->fk_id_departamento); //Busca departamento del empleado
        $request->request->set('fk_id_empresa_empleado_solicitud', Empresas::where('conexion', $company)->first()->id_empresa); // Empresa del empleado que solicitÃ³ el ticket
        $request->request->set('fk_id_estatus_ticket', 2); // Estatus "Abierto"
        $request->request->set('fk_id_modo_contacto', 1); // Se contacÃ³ por medio del sistema de tickets
                                                                                                                                
        $this->validate($request, $this->entity->rules);
        $created = $this->entity->create($request->all());
        
        if($created) {
            $files = Input::file('archivo');
            if(!empty($files)) {
                foreach ($files as $file) {
                    $filename = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$file->getClientOriginalName());
                    $path = $company.'_ticket_'.$created->id_solicitud.'/';
                    $file_save = Storage::disk('tickets')->put($path.$filename, file_get_contents($file->getRealPath()));
                    
                    if($file_save) {
                        $archivos['fk_id_solicitud'] = $created->id_solicitud;
                        $archivos['ruta_archivo'] = Storage::disk('tickets')->getDriver()->getAdapter()->getPathPrefix().$path;
                        $archivos['nombre_archivo'] = $filename;
                        $created->archivosAdjuntos()->save(new ArchivosAdjuntos($archivos));
                    }
                }
            }
            Logs::createLog($this->entity->getTable(), $company, $created->id_solicitud, 'crear', 'Ticket creado');
        }
        else {
            Logs::createLog($this->entity->getTable(), $company, null, 'crear', 'Error al crear ticket');
        }
        
        return redirect(URL::previous());
    }

    public function show($company, $id, $attributes = [])
    {
        $attributes['dataview'] = [
            'datall' => $this->entity->all(),
            'data' => $this->entity->findOrFail($id),
            'conversations' => SeguimientoSolicitudes::where('fk_id_solicitud',$id)->where('activo',1)->orderBy('fecha_hora')->get(),
            'attachments' => ArchivosAdjuntos::where('fk_id_solicitud',$id)->where('activo',1)->where(DB::RAW('fk_id_mensaje'))->get(),
            'employees' => Empleados::selectRaw("id_empleado, concat(nombre,' ',apellido_paterno,' ',apellido_materno) AS empleado")->where('activo',1)->where('fk_id_departamento',18)->pluck('empleado','id_empleado'),
            'status' => EstatusTickets::where('activo',1)->get(),
            'impacts' => Impactos::select('id_impacto','impacto')->where('activo',1)->pluck('impacto','id_impacto'),
            'urgencies' => Urgencias::select('id_urgencia','urgencia')->where('activo',1)->pluck('urgencia','id_urgencia'),
            'categorys' => Categorias::select('id_categoria', 'categoria')->where('activo',1)->pluck('categoria', 'id_categoria'),
            'subcategorys' => Subcategorias::select('id_subcategoria', 'subcategoria')->where('activo',1)->pluck('subcategoria', 'id_subcategoria'),
            'acctions' => Acciones::select('id_accion', 'accion')->where('activo',1)->pluck('accion', 'id_accion'),
            #'employee_department' => Empleados::findOrFail(Usuarios::where('id_usuario', Auth::id())->first()->fk_id_empleado)->fk_id_departamento
        ];
        
        return parent::show($company, $id, $attributes);
    }

    public function update(Request $request, $company, $id, $compact = false)
    {
        $entity = $this->entity->findOrFail($id);
        
        $entity->setAttribute('fecha_hora_resolucion', 'now()');
        $entity->fill($request->all());
        if ($entity->save()) {
            Logs::createLog($this->entity->getTable(), $company, $id, 'editar', 'Registro actualizado');
        } else {
            Logs::createLog($this->entity->getTable(), $company, $id, 'editar', 'Error al editar');
        }
        
        return redirect(companyAction('index')); // Redirigimos a index
    }

    public function obtenerSubcategorias($company, $id)
    {
        return Categorias::all()->find($id)->subcategorias->where('activo',1)->toJson();
    }

    public function obtenerAcciones($company, $id)
    {
        return Subcategorias::all()->find($id)->acciones->where('activo',1)->toJson();
    }

    public function descargarArchivosAdjuntos($company, $id)
    {
        $archivo = ArchivosAdjuntos::where('id_archivo_adjunto', $id)->first();
        if (File::exists($archivo->ruta_archivo.'/'.$archivo->nombre_archivo))
        {
            Logs::createLog($archivo->getTable(), $company, $archivo->id_archivo_adjunto, 'descargar', 'Archivo adjunto de ticket');
            return Response::download($archivo->ruta_archivo . '/' . $archivo->nombre_archivo);
        }
        else {
            App::abort(404,'No se encontro el archivo o recurso que se solicito.');
        }
    }
    
    public function getCategorias($company)
    {
        return Categorias::where('activo',1)->select('id_categoria as id','categoria as text')->get()->toJson();
    }
    
    public function getPrioridades($company)
    {
        return Prioridades::where('activo',1)->select('id_prioridad as id','prioridad as text')->get()->toJson();
    }
}