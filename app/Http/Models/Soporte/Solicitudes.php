<?php

namespace App\Http\Models\Soporte;

use Illuminate\Database\Eloquent\Model;

class Solicitudes extends Model
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
        'fk_id_subcategoria' => 'required',
        'fk_id_accion' => 'required',
        'fk_id_prioridad' => 'required',
	];

    public function empleado()
    {
        return $this->belongsTo('App\Http\Models\RecursosHumanos\Empleados','fk_id_empleado_solicitud','id_empleado');
    }
    public function empleado_tecnico()
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

    public function archivos_adjuntos()
    {
        return $this->hasMany('App\Http\Models\Soporte\ArchivosAdjuntos','fk_id_solicitud');
    }

    public function seguimiento()
    {
        return $this->hasMany('App\Http\Models\Soporte\SeguimientoSolicitudes','fk_id_solicitud','id_solicitud');
    }
}
