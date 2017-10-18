<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelBase;

class TiposProyectos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'pry_cat_tipos_proyectos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_tipo_proyecto';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['tipo_proyecto', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'tipo_proyecto' => 'required'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
        'tipo_proyecto' => 'Tipo proyecto',
        'activo_span' => 'Estatus'
	];

	public $niceNames = [
	    'tipo_proyecto' => 'tipo proyecto'
    ];
}
