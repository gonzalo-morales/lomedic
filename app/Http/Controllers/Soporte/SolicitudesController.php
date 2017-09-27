<?php
namespace App\Http\Controllers\Soporte;
use DB;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Logs;
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
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use App\Http\Models\Soporte\SeguimientoSolicitudes;

class SolicitudesController extends ControllerBase
{

    public function __construct(Solicitudes $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
    }
    
    public function index_tecnicos($company)
    {
        return view(currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->all()
                ->where('eliminar', '0')
                ->where('fk_id_empleado_tecnico', null)
        ]);
    }

    public function index_tecnico($company)
    {
        $id_empleado = Empleados::findOrFail(Usuarios::where('id_usuario', Auth::id())->first()->fk_id_empleado)->id_empleado;
        
        return view(currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->all()
                ->where('eliminar', '0')
                ->where('fk_id_empleado_tecnico', $id_empleado)
        ]);
    }

    public function store(Request $request, $company) // Para crear un nuevo ticket
    {
        $id_solicitante = !empty($request->request->get('empleado_solicitud')) ? $request->request->get('empleado_solicitud') : $request->request->get('id_solicitante');
        
        $request->request->set('fk_id_empleado_solicitud', $id_solicitante);
        $request->request->set('fk_id_departamento', Empleados::find($id_solicitante)->first()->fk_id_departamento); //Busca departamento del empleado
        $request->request->set('fk_id_empresa_empleado_solicitud', Empresas::where('conexion', $company)->first()->id_empresa); // Empresa del empleado que solicitó el ticket
        $request->request->set('fk_id_estatus_ticket', 2); // Estatus "Abierto"
        $request->request->set('fk_id_modo_contacto', 1); // Se contacó por medio del sistema de tickets
                                                                                                                                
        $this->validate($request, $this->entity->rules);
        $created = $this->entity->create($request->all());
        
        if ($created) {
            $files = Input::file('archivo');
            if (Input::hasFile('archivo')) {
                foreach ($files as $file) {
                    $path = public_path() . '\storage\\' . $company . '\ticket' . $created->id_solicitud;
                    $filename = uniqid() . $file->getClientOriginalName();
                    $file->move($path, $filename);
                    $archivo_adjunto = new ArchivosAdjuntos();
                    $archivo_adjunto->fk_id_solicitud = $created->id_solicitud;
                    $archivo_adjunto->ruta_archivo = $path;
                    $archivo_adjunto->nombre_archivo = $filename;
                    $archivo_adjunto->save();
                }
            }
            
            Logs::createLog($this->entity->getTable(), $company, $created->id_solicitud, 'crear', 'Ticket creado');
        } else {
            Logs::createLog($this->entity->getTable(), $company, null, 'crear', 'Error al crear ticket');
        }
        
        return redirect(URL::previous());
    }

    public function show($company, $id, $attributes = [])
    {
        $attributes['dataview'] = [
            'datall' => $this->entity->all(),
            'data' => $this->entity->findOrFail($id),
            'conversations' => SeguimientoSolicitudes::where('fk_id_solicitud',$id)->where('eliminar',false)->where('activo',true)->orderBy('fecha_hora')->get(),
            'attachments' => ArchivosAdjuntos::where('fk_id_solicitud',$id)->where('eliminar',false)->where('activo',true)->where(DB::RAW('fk_id_mensaje'))->get(),
            'employees' => Empleados::select('id_empleado',DB::raw("concat(nombre,' ',apellido_paterno,' ',apellido_materno) AS empleado"))
                ->where('eliminar',false)->where('activo',true)->where('fk_id_departamento',18)->get()->pluck('empleado','id_empleado'),
            'status' => EstatusTickets::where('eliminar',false)->where('activo',true)->get(),
            'impacts' => Impactos::select('id_impacto','impacto')->where('eliminar',false)->where('activo',true)->get()->pluck('impacto','id_impacto'),
            'urgencies' => Urgencias::select('id_urgencia','urgencia')->where('eliminar',false)->where('activo',true)->get()->pluck('urgencia','id_urgencia'),
            'categorys' => Categorias::select('id_categoria', 'categoria')->where('eliminar', '=', 0)->where('activo', '=', 1)->get()->pluck('categoria', 'id_categoria'),
            'subcategorys' => Subcategorias::select('id_subcategoria', 'subcategoria')->where('eliminar', '=', 0)->where('activo', '=', 1)->get()->pluck('subcategoria', 'id_subcategoria'),
            'acctions' => Acciones::select('id_accion', 'accion')->where('eliminar', '=', 0)->where('activo', '=', 1)->get()->pluck('accion', 'id_accion'),
            'employee_department' => Empleados::findOrFail(Usuarios::where('id_usuario', Auth::id())->first()->fk_id_empleado)->fk_id_departamento
        ];
        
        return parent::show($company, $id, $attributes);
    }

    public function update(Request $request, $company, $id)
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
        return Categorias::all()->find($id)->subcategorias->where('activo', '1')->toJson();
    }

    public function obtenerAcciones($company, $id)
    {
        return Subcategorias::all()->find($id)->acciones->where('activo', '1')->toJson();
    }

    public function descargarArchivosAdjuntos($company, $id)
    {
        $archivo = ArchivosAdjuntos::where('id_archivo_adjunto', $id)->first();
        Logs::createLog($archivo->getTable(), $company, $archivo->id_archivo_adjunto, 'descargar', 'Archivo adjunto de ticket');
        return Response::download($archivo->ruta_archivo . '/' . $archivo->nombre_archivo);
    }
}
