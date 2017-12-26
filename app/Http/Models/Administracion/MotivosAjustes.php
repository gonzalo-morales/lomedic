<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class MotivosAjustes extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_motivos_ajustes';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_motivo_ajuste';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['descripcion', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'descripcion'	=> 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'descripcion' => 'Laboratorio',
		'activo_span' => 'Estatus',
	];
}
