<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class SatMunicipios extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.sat_cat_municipios';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_municipio';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['sat_municipio','sat_estado', 'municipio', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'sat_municipio'	=> 'required|max:3|numeric',
		'municipio'     => 'required|max:255',
		'sat_estado'	=> 'required|max:3'
	];

    protected $unique = ['sat_municipio','municipio','sat_estado'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'sat_municipio' => 'Código municipio',
		'municipio' => 'Municipio',
		'sat_estado' => 'Abreviatura Estado',
		'activo_span' => 'Estatus'
	];
}
