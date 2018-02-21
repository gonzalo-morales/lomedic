<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class FormaFarmaceutica extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_forma_farmaceutica';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_forma_farmaceutica';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['forma_farmaceutica','descripcion','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'forma_farmaceutica' => 'required|max:100|regex:/^[a-zA-Z\s]+/',
		'forma_farmaceutica' => 'required|max:255|regex:/^[a-zA-Z\s]+/',
	];

	protected $unique = ['forma_farmaceutica'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'forma_farmaceutica' => 'Forma Farmaceutica',
	    'descripcion' => 'Descripcion',
		'activo_span' => 'Estatus',
	];
}
