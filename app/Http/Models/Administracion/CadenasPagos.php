<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class CadenasPagos extends ModelBase
{
	protected $table = 'sat_cat_cadenas_pagos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_cadena_pago';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['cadena_pago','descripcion','activo'];//Solicitante devoluciÃ³n. false: localidad; true: proveedor;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'cadena_pago' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:255',
		'descripcion' => 'required|max:255'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'cadena_pago' => 'Cadena',
		'descripcion' => 'Descripcion',
		'activo_span' => 'Estatus'
	];

}
