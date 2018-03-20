<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class AgrupadoresCuentas extends ModelBase
{
    // use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'sat_cat_agrupadores_cuentas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_agrupador_cuenta';

	/**
	 * The attributes that are mass assignable.P
	 *
	 * @var array
	 */
	protected $fillable = ['codigo_agrupador', 'nombre_cuenta', 'activo'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'codigo_agrupador' => 'Codigo Agrupador',
		'nombre_cuenta' => 'Nombre Cuenta',
		'activo_span' => 'Estatus'
	];

	protected $unique = ['codigo_agrupador'];
}
