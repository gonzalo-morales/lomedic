<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Sucursales extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.ges_cat_sucursales';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_sucursal';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre_sucursal', 'fk_id_localidad', 'latitud', 'longitud',
		'fk_id_tipo_sucursal','fk_id_red','fk_id_supervisor','fk_id_cliente','embarque','calle',
		'no_interior','no_exterior','colonia','codigo_postal','fk_id_municipio','fk_id_estado',
		'fk_id_pais','registro_sanitario','tipo_batallon','region','zona_militar','telefono1',
		'telefono2','clave_presupuestal','fk_id_jurisdiccion','activo'];

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
		'nombre_sucursal' => 'required|alpha_num',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'nombre_sucursal' => 'Sucursal',
		'fk_id_localidad' => 'Localidad',
		'fk_id_tipo_sucursal' => 'Tipo de Sucursal',
		'fk_id_supervisor' => 'Supervisor',
		'activo_span' => 'Activo',
	];

	/**
	 * Atributos de carga optimizada
	 * @var array
	 */
	protected $eagerLoaders = [];

	public function empleados()
	{
		return $this->hasMany('app\Http\Models\RecursosHumanos\Empleados');
	}

	public function solicitudes()
	{
		return $this->hasMany('app\Http\Models\Soporte\Solicitudes','fk_id_sucursal','id_sucursal');
	}

}
