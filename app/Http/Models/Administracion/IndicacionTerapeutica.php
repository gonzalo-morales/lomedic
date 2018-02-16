<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class IndicacionTerapeutica extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_indicacion_terapeutica';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_indicacion_terapeutica';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['indicacion_terapeutica','descripcion','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'indicacion_terapeutica' => 'required|max:100',
		'descripcion' => 'max:255|regex:/^[a-zA-Z\s]+/'
	];
	protected $unique = ['indicacion_terapeutica'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'indicacion_terapeutica' => 'Indicacion Terapeutica',
	    'descripcion' => 'Descripcion',
		'activo_span' => 'Estatus',
	];
}
