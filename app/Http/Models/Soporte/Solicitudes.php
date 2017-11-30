<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;

class Solicitudes extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'sop_opr_solicitudes';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_solicitud';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['descripcion', 'asunto','fk_id_empleado_solicitud','fk_id_empresa_empleado_solicitud',
        'fk_id_sucursal','fk_id_estatus_ticket','fk_id_categoria','fk_id_subcategoria','fk_id_accion',
        'fk_id_prioridad','fk_id_modo_contacto','fk_id_empleado_tecnico','fk_id_impacto','fk_id_urgencia',
        'resolucion','fecha_hora_resolucion','activo','nombre_solicitante','fk_id_departamento'];
	
	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	    'a_solicitante' => 'Solicitante',
	    'departamento.descripcion' => 'Departamento',
	    'asunto' => 'Asunto',
	    'fecha_hora_creacion' => 'Fecha Creacion',
	    'a_tecnico' => 'Tecnico Asignado',
	    'a_categoria' => 'Categoria',
	    'prioridad_span' => 'Prioridad',
	    'a_estatus'=> 'Estatus',
	];
	
	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = ['a_solicitante','a_estatus','a_tecnico','a_prioridad','a_categoria'];
	
	
	public function getASolicitanteAttribute()
	{
	    return $this->empleado->nombre.' '.$this->empleado->apellido_paterno.' '.$this->empleado->apellido_materno;
	}
	
	public function getAEstatusAttribute()
	{
	    $format = new HtmlString("<span class=".(!empty($this->estatusTickets->color) ? "text-".$this->estatusTickets->color : "").">".$this->estatusTickets->estatus."&nbsp;</span>");
	    return $format; //$this->estatusTickets->estatus;
	}
	
	public function getAPrioridadAttribute()
	{
	    return $this->prioridad->prioridad;
	}
	
	public function getPrioridadSpanAttribute()
	{
	    $icon = !empty($this->prioridad->icono) ? new HtmlString("<i class=material-icons>".$this->prioridad->icono."</i>") :"";
	    $format = new HtmlString("<span class=".(!empty($this->prioridad->color) ? "text-".$this->prioridad->color : "").">$icon $this->a_prioridad&nbsp;</span>");
	    if (request()->ajax()) {
	        return $format->toHtml();
	    }
	    return $format;
	}
	
	public function getACategoriaAttribute()
	{
	    return $this->categoria->categoria;
	}
	
	public function getATecnicoAttribute()
	{
        return empty($this->empleadoTecnico) ? $this->fk_id_empleado_tecnico :$this->empleadoTecnico->nombre.' '.$this->empleadoTecnico->apellido_paterno.' '.$this->empleadoTecnico->apellido_materno;
	}

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'descripcion' => 'required',
        'asunto' => 'required',
        'fk_id_empleado_solicitud' => 'required',
        'fk_id_categoria' => 'required',
        //'fk_id_subcategoria' => 'required',
        //'fk_id_accion' => 'required',
        'fk_id_prioridad' => 'required',
	];

    public function empleado()
    {
        return $this->belongsTo('App\Http\Models\RecursosHumanos\Empleados','fk_id_empleado_solicitud','id_empleado');
    }
    public function departamento()
    {
        return $this->belongsTo('App\Http\Models\RecursosHumanos\Departamentos','fk_id_departamento','id_departamento');
    }
    public function empleadoTecnico()
    {
        return $this->belongsTo('App\Http\Models\RecursosHumanos\Empleados','fk_id_empleado_tecnico','id_empleado');
    }
    public function sucursal()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }

    public function estatusTickets()
    {
        return $this->hasOne('App\Http\Models\Soporte\EstatusTickets','id_estatus_ticket','fk_id_estatus_ticket');
    }

    public function categoria()
    {
        return $this->hasOne('App\Http\Models\Soporte\Categorias','id_categoria','fk_id_categoria');
    }

    public function subcategoria()
    {
        return $this->hasOne('App\Http\Models\Soporte\Subcategorias','id_subcategoria','fk_id_subcategoria');
    }

    public function accion()
    {
        return $this->hasOne('App\Http\Models\Soporte\Acciones','id_accion','fk_id_accion');
    }

    public function prioridad()
    {
        return $this->hasOne('App\Http\Models\Soporte\Prioridades','id_prioridad','fk_id_prioridad');
    }

    public function modocontacto()
    {
        return $this->hasOne('App\Http\Models\Soporte\ModosContacto','id_modo_contacto','fk_id_modo_contacto');
    }

    public function impacto()
    {
        return $this->hasOne('App\Http\Models\Soporte\Impactos','id_impacto','fk_id_impacto');
    }

    public function urgencia()
    {
        return $this->hasOne('App\Http\Models\Soporte\Urgencias','id_urgencia','fk_id_urgencia');
    }

    public function archivosAdjuntos()
    {
        return $this->hasMany('App\Http\Models\Soporte\ArchivosAdjuntos','fk_id_solicitud');
    }

    public function seguimiento()
    {
        return $this->hasMany('App\Http\Models\Soporte\SeguimientoSolicitudes','fk_id_solicitud','id_solicitud');
    }
}
