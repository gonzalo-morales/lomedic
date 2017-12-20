<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class FormasPago extends ModelBase
{
    // use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.sat_cat_formas_pago';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_forma_pago';

	/**
	 * The attributes that are mass assignable.P
	 *
	 * @var array
	 */
	protected $fillable = ['forma_pago', 'descripcion', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'forma_pago'	=> 'required|max:5',
		'descripcion'	=> 'required|max:255',
		'activo'		=> 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'forma_pago' => 'No. Forma de Pago',
		'descripcion' => 'Descripcion',
		'activo_span' => 'Estatus'
	];


}
