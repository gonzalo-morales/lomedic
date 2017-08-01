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
        'resolucion','fecha_hora_resolucion','activo'];

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
        'fk_id_estatus_ticket' => 'required',
        'fk_id_categoria' => 'required',
        'fk_id_prioridad' => 'required',
        'fk_id_modo_contacto' => 'required',
	];

    public function empleados()
    {
        return $this->belongsTo('App\Http\Models\RecursosHumanos\Empleados');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales');
    }

    public function estatusTickets()
    {
        return $this->hasOne('App\Http\Models\Soporte\EstatusTickets');
    }

    public function categoria()
    {
        return $this->hasOne('App\Http\Models\Soporte\Categorias');
    }

    public function subcategoria()
    {
        return $this->hasOne('App\Http\Models\Soporte\Subcategorias');
    }

    public function accion()
    {
        return $this->hasOne('App\Http\Models\Soporte\Acciones');
    }

    public function prioridad()
    {
        return $this->hasOne('App\Http\Models\Soporte\Prioridades');
    }

    public function modocontacto()
    {
        return $this->hasOne('App\Http\Models\Soporte\ModosContacto');
    }

    public function impacto()
    {
        return $this->hasOne('App\Http\Models\Soporte\Impactos');
    }

    public function urgencia()
    {
        return $this->hasOne('App\Http\Models\Soporte\Urgencias');
    }
}
