<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Diagnosticos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_diagnosticos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_diagnostico';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['clave_diagnostico', 'diagnostico', 'medicamento_sugerido',];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'clave_diagnostico' => 'required',
		'diagnostico' => 'required',
		'medicamento_sugerido' => 'required',
	];

	/**
	 * Atributos visibles en toArray/toJson
	 * @var array
	 */
	protected $visible = ['id_diagnostico', 'clave_diagnostico', 'diagnostico','medicamento_sugerido'];

	/**
	 * Los atributos que seran visibles en smart-datatable
	 * @var array
	 */
	protected $fields = [
		'clave_diagnostico' => 'Clave',
		'diagnostico' => 'Diagnostico',
		'medicamento_sugerido' => 'Medicamento Sugerido'
	];

}
