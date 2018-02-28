<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelCompany;

class SeriesSkus extends ModelCompany
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_series_sku';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_serie_sku';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nombre_serie',
		'prefijo',
		'sufijo',
		'primer_numero',
		'numero_siguiente',
		'ultimo_numero',
		'descripcion',
		'activo'
	];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'nombre_serie' => 'max:60|required|regex:/^[a-zA-Z\s]+$/',
		'prefijo' => 'max:10|required',
		'sufijo' => 'max:255|required',
		'primer_numero' => 'required|numeric',
		'numero_siguiente' => 'required|numeric',
		'ultimo_numero' => 'required|numeric',
		'descripcion' => 'max:255|required|regex:/^[a-zA-Z\s]+$/',
	];

	protected $unique = [
		'nombre_serie',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'nombre_serie' => 'Nombre Serie',
	    'descripcion' => 'Descripcion',
	    'prefijo' => 'Prefijo',
	    'sufijo' => 'Sufijo',
	    'primer_numero' => 'Primer Numero',
	    'numero_siguiente' => 'Numero Siguiente',
	    'ultimo_numero' => 'Ultimo Numero',
		'activo_span' => 'Estatus',
	];
}