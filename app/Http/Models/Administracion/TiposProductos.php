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
	protected $table = 'maestro.gen_cat_tipo_producto';

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
	protected $fillable = ['tipo_producto','nomenclatura','prioridad', 'activo'];

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
        'tipo_producto' => 'Tipo',
        'nomenclatura' => 'Nomenclatura',
        'prioridad' => 'Prioridad',
        'activo_span' => 'Estatus',
    ];

}
