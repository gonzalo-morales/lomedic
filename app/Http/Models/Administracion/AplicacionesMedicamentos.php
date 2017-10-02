<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class AplicacionesMedicamentos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_aplicaciones_medicamentos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_aplicacion';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['aplicacion', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'aplicacion' => ['required','min:2','regex:/^[a-zA-Z\s]+/']
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'aplicacion' => 'Aplicación',
		'activo_span' => 'Estatus'
	];
}
