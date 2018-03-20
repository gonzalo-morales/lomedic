<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Tipocombustible extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_tipo_combustible';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_combustible';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['combustible', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = ['combustible' => 'required|max:20|regex:/^[a-zA-Z\s]+/'];

	protected $unique = ['combustible'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	  'combustible' => 'Combustible',
	];

}
