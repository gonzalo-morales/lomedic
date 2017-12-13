<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\ModelBase;
use App\Http\Models\RecursosHumanos\Empleados;

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
	protected $fillable = ['sucursal', 'fk_id_tipo', 'fk_id_localidad', 'fk_id_zona',
		'fk_id_cliente', 'responsable', 'telefono_1', 'telefono_2', 'calle',
		'numero_interior', 'numero_exterior', 'colonia', 'codigo_postal',
		'fk_id_pais', 'fk_id_estado', 'fk_id_municipio', 'latitud', 'longitud',
		'registro_sanitario', 'inventario', 'embarque', 'tipo_batallon', 'region',
		'zona_militar', 'clave_presupuestal', 'id_localidad_proveedor', 'id_jurisdiccion','activo'
	];

	/**
	 * The validation rules
	 * @var array
	 */
	// public $rules = [
	// 	'sucursal' => 'required',
	// 	'fk_id_tipo' => 'required',
	// 	'fk_id_localidad' => 'required',
	// 	'responsable' => 'required',
	// 	'telefono_1' => 'required',
	// 	'calle' => 'required',
	// 	'numero_interior' => 'required',
	// 	'numero_exterior' => 'required',
	// 	'colonia' => 'required',
	// 	'codigo_postal' => 'required',
	// 	'fk_id_pais' => 'required',
	// 	'fk_id_estado' => 'required',
	// 	'fk_id_municipio' => 'required',
	// 	'inventario' => 'required',
	// 	'embarque' => 'required',
	// ];

	public $niceNames = [
		'fk_id_localidad' => 'localidad',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'sucursal' => 'Sucursal',
		'localidad.localidad' => 'Localidad',
		'tiposucursal.tipo' => 'Tipo de Sucursal',
		'activo_span' => 'Activo',
	];

	/**
	 * Atributos de carga optimizada
	 * @var array
	 */
	protected $eagerLoaders = ['localidad','tiposucursal'];

	public function tiposucursal() {
		return $this->hasOne(TipoSucursal::class, 'id_tipo', 'fk_id_tipo');
	}

	public function localidad() {
		return $this->hasOne(Localidades::class, 'id_localidad', 'fk_id_localidad');
	}

	public function solicitudes()
	{
		return $this->hasMany('app\Http\Models\Soporte\Solicitudes','fk_id_sucursal','id_sucursal');
	}

	/**
	 * Obtenemos almacenes relacionados a sucursal
	 * @return @hasMany
	 */
	public function almacenes()
	{
		return $this->hasMany(Almacenes::class, 'fk_id_sucursal', 'id_sucursal');
	}

	public function empleados()
    {
        return $this->belongsToMany(Empleados::class,'maestro.ges_det_empleado_sucursal','fk_id_sucursal','fk_id_empleado','id_sucursal');
    }
}
