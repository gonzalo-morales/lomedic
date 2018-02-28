<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Patrones extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.sat_cat_patrones';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_patron';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['patron', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'patron'	=> 'required|max:255|regex:/^[a-zA-Z\s]+/',
	];

    protected $unique = ['patron'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'patron' => 'Parentesco',
		'activo_span' => 'Estatus'
	];
}