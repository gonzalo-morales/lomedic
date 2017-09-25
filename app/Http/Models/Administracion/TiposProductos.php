<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposProductos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_tipo_producto';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_tipo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['descripcion', 'estatus','nomenclatura','prioridad'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'descripcion' => 'required',
		'estatus' => 'required',
		'nomenclatura' => 'required',
		'prioridad' => 'required',
	];

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'descripcion' => 'Tipo',
        'tipo' => 'Tipo',
        'nomenclatura' => 'Nomenclatura',
        'prioridad' => 'Prioridad',
        'estatus' => 'Estatus',
    ];

}
