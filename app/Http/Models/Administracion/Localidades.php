<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Localidades extends ModelBase
{
	protected $table = 'maestro.gen_cat_localidades';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_localidad';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['localidad','activo'];

	protected $unique = ['localidad'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'localidad' => 'required|regex:/^[a-zA-Z\s]+/|max:30'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'localidad' => 'Localidad',
		'activo_span' => 'Estatus'
	];
}