<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class ViaAdministracion extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_via_administracion';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_via_administracion';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['via_administracion', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'via_administracion' => 'Via Administracion',
		'activo_span' => 'Estatus',
	];
}
