<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Parentescos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_parentescos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_parentesco';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'activo'];

	protected $unique = ['nombre'];
	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'nombre'=> ['required','regex:/^[a-zA-Z\s]+/','max:60']
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'nombre' => 'Parentesco',
		'activo_span' => 'Estatus'
	];
}